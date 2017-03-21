<template>
  <div id="pricelist">
  <h3>{{ client.name }}</h3>
      <div class="row" v-if="message">
        <div class="col-sm-12 message"><span  v-text="message"></span></div>
      </div>
      <div class="row">
        <div class="col-sm-3">Product code</div>
        <div class="col-sm-3">Std price</div>
        <div class="col-sm-3">Client price</div>
        <div class="col-sm-3">Updated price</div>
      </div>
      <div class="row">
        
        <div class="col-sm-3"><input type="text" v-model="add_product_code" ></div>
        <div class="col-sm-3 col-sm-offset-3"><input type="text" v-model="add_product_price" ></div>
        <div class="col-sm-3"><button class="btn" @click="addProduct">Add product</button></div>
        
      </div>
      <div v-for="price in prices" track-by="id" class="row">
        <div class="col-sm-3">{{ price.product_code }}</div>
        <div class="col-sm-3">{{ price.std_price }}</div>
        <div class="col-sm-3">{{ price.client_price }}</div>
        <div class="col-sm-3"><input v-model="price.updated_price"> <i @click="deleteProduct(price.product_code)" class="fa fa-minus-circle" aria-hidden="true"></i></i></div>
        

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
      add_product_price: 0,
      message: ''
    };
  },
  methods: {
    
    saveChanges() {
      let updates = [];
      _.each(this.prices, function(price){
        if(price.client_price !== price.updated_price){
          updates.push({product_code: price.product_code,price: price.updated_price});
        }
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
        client_price: this.add_product_price,
        action: 'add_product',
      };

      this.message = "adding ...." + this.add_product_code;
      this.remote(data).then(function() {
        this.message = '';
      }.bind(this));

      // Clear ouit the input fields
      this.add_product_code ='';
      this.add_product_price = 0;
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
            price.updated_price = price.client_price;
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