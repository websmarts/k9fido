<template>
  <div id="list">
    
      <div v-for="price in updatedPrices" track-by="id" class="row">
        <div class="col-sm-3">{{ price.product_code }}</div>
        <div class="col-sm-3">{{ price.client_price }}</div>
        <div class="col-sm-3"><input v-model="price.client_price" @blur="doneEdit(price)" ></div>

      </div>

  </div>
</template>

<script>
export default {
  props:['prices'],
  data () {
    return {
      updatedPrices: []
    };
  },
  methods: {
    doneEdit: function(price){
      $.each(this.prices,function(idx,val){
        if(val.product_code == price.product_code){
            console.log('price was: '+ val.client_price);
            console.log('price now: '+ price.client_price);
          
          
        }
      })
      
    }
  },
  ready: function() {
    console.log(this.prices);
    self=this;
     $.each(this.prices,function(idx,val) {
      console.log(val);
        self.updatedPrices.push({
          'id': val.id, 
          'product_code': val.product_code,
          'client_price': val.client_price
          });
     });
    console.log('updatedPrices',this.updatedPrices);
  }
};
</script>

<style>
.profile {
    background: #eee;
    border: 1px solid #aaa;
    border-radius: 2em;
    margin: 2em auto;
    min-height: 150px;
    padding: 2em;
    width: 300px;
}
</style>