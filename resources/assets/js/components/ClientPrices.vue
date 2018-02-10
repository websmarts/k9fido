<template>
  <div id="pricelist">
  <h3>{{ client.name }}</h3>
      <div class="row" v-if="message">
        <div class="col-sm-12 message"><span  v-text="message"></span></div>
      </div>
      
      <div class="row" style="background: #eee;padding-top:10px;padding-bottom: 8px;">
       
        <div class="col-sm-4"><input type="text" v-model="add_product_code" > Product code</div>

        <div class="col-sm-3"><input type="text" v-model="add_product_discount" > Discount</div>
        <div class="col-sm-2 col-md-offset-2"><button class="btn btn-primary" @click="addProduct">Add client price</button></div>
        
      </div>
      <div class="row">
        <div class="col-sm-2"><strong>Product code</strong></div>
        <div class="col-sm-2"><strong>Std price : latest</strong> </div>
        <div class="col-sm-2"><strong>Discount</strong></div>
        <div class="col-sm-2"><strong>Client price</strong></div>
        <div class="col-sm-3"><strong>Updated discount</strong></div>
      </div>
      
      <div v-for="price in prices" track-by="id" class="row">
        <div class="col-sm-2">{{ price.product_code }}</div>
        <div class="col-sm-2">{{ price.std_price }}<span v-if="price.std_price != price.latest_std_price"> : {{ price.latest_std_price }}</span></div>
        <div class="col-sm-2">{{ price.discount.toFixed(3) }}<span v-if="price.std_price != price.latest_std_price"> : {{ newDiscount(price) }}</span> </div>
        <div class="col-sm-2">{{ price.client_price }}</div>
        <div class="col-sm-3"><input v-model="price.updated_discount"> <i @click="deleteProduct(price.product_code)" class="fa fa-minus-circle" aria-hidden="true"></i></i></div>
        

      </div>
      <button @click="saveChanges" class="btn btn-primary pull-right">Save</button>

  </div>
</template>

<script>
export default {
  props:['json_client','url'],
  data () {
    return {
      prices:[],
      client:{},
      add_product_code: '',
      add_product_discount: null,
      message: ''
    };
  },
  methods: {

    newDiscount(price) {
      
      return (1-(price.client_price/price.latest_std_price)).toFixed(3)
    },
    
    saveChanges() {
      let updates = [];
      _.each(this.prices, function(price){
        
          updates.push({product_code: price.product_code,discount: price.updated_discount});
        
      })

      if(updates.length > 0) {
          let data = {  
          client_id: this.client.client_id,
          updates: updates,
          action: 'update_prices',
        };
      
        this.message = "saving ....";
        this.remote(data).then(function() {
          this.message = '';
        }.bind(this));
      }

    },
    addProduct() {
      let data={  
        client_id: this.client.client_id,
        product_code: this.add_product_code,
        discount: this.add_product_discount,
        action: 'add_product',
      };

      this.message = "adding ...." + this.add_product_code;
      this.remote(data).then(function() {
        this.message = '';
      }.bind(this));

      // Clear ouit the input fields
      this.add_product_code ='';
      this.add_product_discount = null;
    },
    deleteProduct(productCode) {
      let data={
        client_id: this.client.client_id,
        product_code: productCode,
        action: 'delete_product',
      };

      this.message = "deleting ....";
      this.remote(data).then(function() {
        this.message = '';
      }.bind(this));
    },
    getPriceList() {
      let data={
        client_id: this.client.client_id,
        action: 'get_client_prices',
      };
      this.message = "loading prices ....";
      this.remote(data).then(function() {
        this.message = '';
      }.bind(this));
    },
    remote(data){
      return this.$http.post(this.url, data).then((response) => {
        // success callback
        //console.log(response);
        let prices = response.body;
        this.prices = [];
        _.each(prices, function(price){
            price.updated_discount = price.discount;
            this.prices.push(price);
        }.bind(this));

      }, (response) => {
        // error callback
        //console.log(response);
      });
    }
  },
  mounted() {
    this.client = JSON.parse(this.json_client);
    this.getPriceList();
  }
};
</script>

<style lang="sass">
#pricelist {
  input {
    width: 6em;
  }

  .fa-minus-circle {
    color: #ff0000;
  }

  .message {
    padding:10px;
    color:white;
    background:blue;
  }

}
</style>