<template>
  <div class="item" v-bind:class="picked"> 
      <h3>{{ item.product_code }}<span class="pull-right" v-show="!barcodeCheck()">Barcode: {{ item.barcode }}</span></h3>
      <h4 v-html="item.description"></h4>
      <div>Qty to pick:{{ item.qty - item.qty_supplied }} ({{ item.qty }}) <input type="number" v-bind:id="itemId(item.id)" v-model="item.input" class="input" v-on:input.prevent="itemInput" />
      </div>
      <div class="product_note" v-if="item.product_note">{{ item.product_note }}</div>       
  </div>
</template>

<script>
export default {
  props: ['item'],
  methods: {
    itemInput: function() {
        // console.log(this)
        // Check if input contains the barcode string
        let inputString = String(this.item.input);
        let barcodeString = String(this.item.barcode).trim().substring(0,12);
        let isBarcode = -1;

        //console.log([inputString.length, barcodeString.length]);

        // skip if barcode is set to 0 - ie no barcode
        if(parseInt(barcodeString) !== 0 ) {
          isBarcode = inputString.indexOf(barcodeString);
        } 
        
        
        // console.log(position);
        if(isBarcode > -1) {
          // input contains the correct barcode ignore the rest
          this.item.input = parseInt(barcodeString);
        }

        let value = isNaN(this.item.input) ? 0 : parseInt(this.item.input);
        
        
        // check if the barcode string is in the input value
        if(this.item.barcode === value) {
            this.item.scanned_barcode = this.item.barcode

            if((this.item.qty - this.item.qty_supplied) > this.item.picked_qty) {
                this.item.picked_qty++
            }
        } else {
            if(this.barcodeCheck()){
                
                if (value > 0 ){
                    this.item.picked_qty = value
                }
            }
            
        }
        //console.log('value:' + value)
        // check if input looks like a qty and not a barcode value
        if(value > 0){
          //console.log('setting input to picked qty:'+ this.item.picked_qty)
            this.item.input = this.item.picked_qty > 0 ? this.item.picked_qty : '';
        } else {
            this.item.picked_qty = '';
            this.item.input ='';
        }


        if (this.isPicked()){
          this.$emit('picked',this.item.product_code);
        }
        
    },
    
    quantityCheck() {
        return (this.item.qty - this.item.qty_supplied) == this.item.picked_qty
        
    },
    barcodeCheck() {
         return this.item.barcode === this.item.scanned_barcode
      
    },
    itemId(id) {
      return "item_" + id;
    },
    isPicked() {
        return this.quantityCheck() && this.barcodeCheck()
    },
    
    

  },
  computed: {
    picked() {
      let picked = this.isPicked();
      return {
            complete: picked  
        }
    }
    
  }
}

</script>

<style>
.product_note {
  background: #ffffff;
  color: #ff0000;
}

</style>