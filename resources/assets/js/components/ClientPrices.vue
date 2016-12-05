<template>
  <div id="pricelist">
  <h3>{{ client.name }}</h3>

     <div class="row">
        <div class="col-sm-3">Product code</div>
        <div class="col-sm-3"><input type="text" v-model="add_product_code" ></div>
        <div class="col-sm-3"><button class="btn" @click="addProduct">Add product</button></div>
        <div class="col-sm-3"><span v-text="add_message"></span></div>
      </div>
      <div class="row">
        <div class="col-sm-3">Product code</div>
        <div class="col-sm-3">Std price</div>
        <div class="col-sm-3">Client price</div>
        <div class="col-sm-3">Updated price</div>
      </div>
      <div v-for="price in prices" track-by="id" class="row">
        <div class="col-sm-3">{{ price.product_code }}</div>
        <div class="col-sm-3">{{ price.std_price }}</div>
        <div class="col-sm-3">{{ price.client_price }}</div>
        <div class="col-sm-3"><input v-model="price.updated_price"> <i @click="deleteProduct(price)" class="fa fa-minus-circle" aria-hidden="true"></i></i></div>
        

      </div>
      <button @click="saveChanges" class="btn btn-primary pull-right">Save</button>

  </div>
</template>

<script>
export default {
  props:['json_prices','json_client','url'],
  data () {
    return {
      prices:[],
      client:{},
      add_product_code: '',
      add_message: ''
    };
  },
  methods: {
    doneEdit(price){
      $.each(this.prices,function(idx,val){
        if(val.product_code == price.product_code){
            console.log('price was: '+ val.client_price);
            console.log('updated price: '+ price.updated_price);
          
          
        }
      })
      
    },
    saveChanges() {
      console.log(this.prices);
    },
    addProduct() {
      console.log('Add product = ' + this.add_product_code)
      let data={
        prices: this.prices,
        client: this.client,
        add_product_code: this.add_product_code,
        action: 'add_product',

      };
    
       this.$http.post(this.url, data).then((response) => {
        // success callback
        console.log(response);
      }, (response) => {
        // error callback
        console.log(response);
      });
      this.add_message = 'success';
    },
    deleteProduct(product) {
      console.log(' deleteing product =' + product);
    }

  },
  mounted() {
    this.client = JSON.parse(this.json_client);
    this.prices = JSON.parse(this.json_prices);
    console.log(this.prices);
    self=this;
     $.each(this.prices,function(idx,val) {
      val['updated_price'] = val.client_price;
     });
    console.log('Prices',this.prices);
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

}
</style>