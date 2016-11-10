<template>
  <div class="item" v-bind:class="picked"> 
      <h3>{{ item.product_code }}</h3>
      <h4>{{ item.description }}</h4>
      <div>Qty:({{ item.qty - item.qty_supplied }}:{{ item.qty }}) <input v-bind:id="itemId(item.id)" v-model="item.input" class="input" v-on:keyup.prevent="itemInput" />
      <br /><span>barcode: {{ item.barcode }}</span></div>       
  </div>
</template>

<script>
export default {
  props: ['item'],
  methods: {
    itemInput: function() {
        // console.log(this)
        // Check if input concontains the barcode string
        let inputString = String(this.item.input);
        let barcodeString = String(this.item.barcode);

        //console.log([inputString.length, barcodeString.length]);

        let position = inputString.indexOf(barcodeString);
        
        // console.log(position);
        if(position > -1) {
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
            if(this.item.barcode == this.item.scanned_barcode){
                
                if (value > 0 ){
                    this.item.picked_qty = value
                }
            }
            
        }
        console.log('value:' + value)
        // check if input looks like a qty and not a barcode value
        if(value > 0){
          console.log('setting input to picked qty:'+ this.item.picked_qty)
            this.item.input = this.item.picked_qty > 0 ? this.item.picked_qty : '';
        } else {
            this.item.picked_qty = '';
            this.item.input ='';
        }


        if (this.isPicked()){
          this.$emit('picked');
        }
        
    },
    
    quantityCheck() {
        return (this.item.qty - this.item.qty_supplied) == this.item.picked_qty
        
    },
    barcodeCheck() {
         return this.item.barcode == this.item.scanned_barcode
      
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