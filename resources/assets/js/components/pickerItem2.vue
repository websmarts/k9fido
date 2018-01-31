<template>
  <div class="item" v-bind:class="picked"> 
      <h3>{{ item.product_code }}<span class="pull-right" v-show="!barcodeCheck()">Barcode: {{ item.barcode }}</span></h3>
      <h4 v-html="item.description"></h4>
      <div>Qty to pick:{{ item.qty - item.qty_supplied }} ({{ picked_qty }}) 
        <input type="number" 
          :id="itemId(item.id)" 
          :value="input" 
          class="input" 
          @input.prevent="itemInput($event.target.value)" />
      </div>
      <div class="product_note" v-if="item.product_note">{{ item.product_note }}</div>       
  </div>
</template>

<script>
export default {
  props: ['item'],
  data() {
    return {
      qty: this.item.qty,
      qty_supplied: this.item.qty_supplied,
      input: this.item.input,
      scanned_barcode: this.item.scanned_barcode,
      picked_qty: this.item.picked_qty
    }
  },
  methods: {
    itemInput: function(val) {
        //console.log("VAL",val)
        // Check if input contains the barcode string
        
        
        let inputString = String(val);
        let barcodeString = String(this.item.barcode);
        let isBarcode = -1;

        //console.log([inputString.length, barcodeString.length]);

        // skip if barcode is set to 0 - ie no barcode
        if(parseInt(barcodeString) !== 0 ) {
          isBarcode = inputString.indexOf(barcodeString);
        } 
        
        
        // console.log(position);
        if(isBarcode > -1) {
          // input contains the correct barcode ignore the rest
          this.input = parseInt(barcodeString);
        }

        let value = isNaN(val) ? 0 : parseInt(val);
        
        
        // check if the barcode string is in the input value
        if(this.item.barcode === value) {
            this.scanned_barcode = this.item.barcode // set flag to show we have seen the correct barcode

            if((this.item.qty - this.item.qty_supplied) > this.picked_qty) { 
                this.picked_qty++
            }
        } else {
            if(this.barcodeCheck()){ // True if scanned barcode  eq product barcode
                
                if (value > 0 ){
                    this.picked_qty = value
                }
            }
            
        }
        //console.log('value:' + value)
        // check if input looks like a qty and not a barcode value
        if(value > 0){
          //console.log('setting input to picked qty:'+ this.item.picked_qty)
            this.input = this.picked_qty > 0 ? this.picked_qty : '';
        } else {
            this.picked_qty = '';
            this.input ='';
        }


        if (this.isPicked()){
          let payload = {
            product_code: this.item.product_code,
            scanned_barcode: this.scanned_barcode,
            input: this.input,
            picked_qty: this.picked_qty

          }
          this.$emit('picked',payload);
        }
        
    },
    
    quantityCheck() {
        return (this.item.qty - this.qty_supplied) == this.picked_qty
        
    },
    barcodeCheck() {
         return this.item.barcode === this.scanned_barcode
      
    },
    itemId(id) {
      return "item_" + id;
    },
    isPicked() {
        return this.quantityCheck() && this.barcodeCheck()
    },
    
    

  },
  computed: {
    picked() { // class setter based on picked status
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