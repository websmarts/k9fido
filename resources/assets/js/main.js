import Vue from 'vue';

Vue.config.devtools = true;

var VueResource = require('vue-resource');
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name=csrf-token').getAttribute('content');
window.vm = new Vue({
  el: '#app',
  data: {
  	prices: prices,
  	newprices: [],
  	product_code: '',
  	client_price: ''
  },
  methods: {
    doneEdit: function(price){
      $.each(this.prices,function(idx,val){
        if(val.product_code == price.product_code){
            console.log('price was: '+ val.client_price);
            console.log('price now: '+ newprice.client_price);
          
          
        }
      })   
    },
    addItem: function(){
    	// Save new price to server
    	// POST /someUrl
    	var data = {client_id: window.client_id, product_code: this.product_code, client_price: this.client_price}
    	self = this;
    	this.$log(self.newprices);
		  this.$http.post(window.url, data).then((response) => {
		    // success callback
		    console.log(response.data.id);
		    for(var i = 0; i < self.newprices.length; i++) {
			    if(self.newprices[i].id == response.data.id) {
			        self.newprices.splice(i, 1);
			        break;
			    }
			}


		  }, (response) => {
		    // error callback
		  });
    	// result is an price obj, push to prices and newprices
    	//this.newprices.push({product_code: this.product_code, client_price: this.client_price});
    }
},
  ready: function() {
    var self = this;
     $.each(prices,function(idx,val) {
      
        self.newprices.push({
          'id': val.id, 
          'product_code': val.product_code,
          'client_price': val.client_price
          });
     });
    console.log('newprices',this.newprices);
  }
  
});