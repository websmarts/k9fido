<template>
  <div class="item" v-bind:class="picked"> 
      <h3>{{ item.product_code }}<span class="pull-right" v-show="!barcodeCheck()">Barcode: {{ item.barcode }}</span></h3>
      <h4 v-html="item.description"></h4>
      <div>Qty to pick:{{ item.qty - item.qty_supplied }} ({{ picked_qty }}) 
        <input type="number" 
          :id="itemId(item.id)" 
          :value="input" 
          class="input" 
          @input="itemInput($event.target.value)" 
          ref="user_input"
          />
      </div>
      <div class="product_note" v-if="item.product_note">{{ item.product_note }}</div>       
  </div>
</template>

<script>
export default {
  props: ['item','autofocus'],
  data() {
    return {
      qty: this.item.qty,
      qty_supplied: this.item.qty_supplied,
      input: this.item.input,
      picked_qty: this.item.picked_qty,
      barcodeString: this.item.barcode.toString(),
      scan_match: false,
    }
  },
  methods: {
    itemInput: function(val) {

      //input = isNaN(input) ? 0 : input,
      //console.log('isNaN=',isNaN(input))

      let strInput = val.toString().trim()

      //console.log('isNaN.toString()trim()=',isNaN(input))
  
      //console.log('input=|',input,'|')

  
        if(this.item.barcode == 0 ) {
          if(this.isScannerInput(val)){
            // console.log('do nothing as erroneous scanner input')
            this.$forceUpdate() // remove the offending input characters
            return
          }
          this.picked_qty =  val < 1 ? null : val // no negetive numbers and null if zero
          this.input = this.picked_qty
          this.$forceUpdate() // important to update input
        } else {
          //console.log('Barcode is not zero')
         
          //console.log('string input',strInput);
          //console.log('barcode string',this.barcodeString)
          // We need to match the item barcode
          let inputContainsBarcode = strInput.indexOf(this.barcodeString)

          //console.log('inputContainsBarcode',inputContainsBarcode)

          if (! this.scan_match && inputContainsBarcode > -1){ // first matching barcode scan
            //console.log('scan match is NOT set and input contains barcode')
            this.scan_match = true;
            this.picked_qty = 1
            this.input = this.picked_qty

          } else if (this.scan_match && inputContainsBarcode > -1) { // additional matching barcode scann
            //console.log('scan match is true yet and  input contains barcode')
            this.picked_qty++
            this.input = this.picked_qty

          } else if (this.scan_match && inputContainsBarcode < 0) { // input is just a qty after a previous matching scan 
            //console.log('previous scan match so treating input as a pick qty')
            
            // Do not accept input less than zero
             


            if (!this.isScannerInput(val)) {
              // The test above filters out the case there an incorrect scan is made in the input AFTER a match has 
              // already been made. 

              this.picked_qty = val < 1 ? null : val // no negetive numbers and null if zero 
              this.input = this.picked_qty
            } 
            this.$forceUpdate()

          } else {
            //console.log('just returning because barcode is not zero and input has no barcode match yet')
            this.$forceUpdate()
            return
          }
         
        }
        // Could add source ie scanner or typed input to payload if
        // we decide the auto advancing is an issue when qty is manually being typed in and 
        // is equal to or exceeds the qty to be picked
        // eg payolad.source : 'scanner' || 'keypress'
         
          let payload = {
            product_code: this.item.product_code,
            scanned_barcode: this.scanned_barcode,
            input: this.input,
            picked_qty: this.picked_qty

          }
          this.$emit('picked',payload);
        
        
      
        
    },
    isPicked() {
        return this.quantityCheck() && this.barcodeCheck()
    },
    isOverPicked() {
      return this.barcodeCheck()  && ( (this.item.qty - this.qty_supplied) < this.picked_qty )
    },
    quantityCheck() {
        return (this.item.qty - this.qty_supplied) == this.picked_qty
        
    },
    barcodeCheck() {
         return this.scan_match || ! this.item.barcode // we have a barcode match or the barcode is 0
      
    },
    itemId(id) {
      return "item_" + id;
    },  
    isScannerInput(val){
      let strInput = val.toString()
      return strInput.length > 7 // anything more than 7 chars is considered a scanned input val
    }
  },
  computed: {
    picked() { // class setter based on picked status
      if (this.isPicked()) {
        return {
            complete: true  
        }
      }

      if(this.isOverPicked()){
        return {
            complete: false,
            overpicked: true  
        }
      }  
    },  
  },
  mounted() {
    if(this.autofocus){
      this.$refs.user_input.focus()
      //this.$nextTick(() => this.$refs.user_input.focus())
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