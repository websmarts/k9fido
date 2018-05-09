<template>
  <div>
  <h3>Stock Adjuster</h3>
  
  <p>Enter Barcode OR a Product Code and then click the <strong>Find item</strong> button</p>
  <div :class="{error: error}">{{message}}</div>
  
    <div style="display:flex">
      <div style="flex:1">Barcode:</div>
      <div style="flex:1"><input 
        name="barcode" 
        type="number"
        :value="barcode" 
        ref="barcode_input"
        @input="barcodeInput($event.target.value)" 
        @focus="focusOn($event.target)" 
        style="width:8em"  /></div>
    </div>

    <div style="display:flex;width:100%;">
      <div style="flex:1">P.code:</div>
      <div style="flex:1"><input 
        name="product_code" 
        ref="product_code_input" 
        type="text":value="product_code" 
        @input="productCodeInput($event.target.value)" 
        @focus="focusOn($event.target)" 
        style="width:8em" /></div>
    </div>
    
    <div style="display:flex;width:100%;">
      <div style="flex:1">&nbsp;</div>
      <div style="flex:1" ><button :class="{loaded: loaded,error: error}" @click="findItem">Find item</button></div>
    </div>

    <div style="border-top:1px dashed #999; margin:15px 0"></div>

    <div style="display: flex;width:100%">
      <div style="flex:1">Qty on order:</div>
      <div style="flex:1">{{ qty_ordered }}</div>
    </div>

    <div style="display:flex;width:100%">
      <div style="flex:1">Qty available:</div>
      <div style="flex:1">{{ qty_available }}</div>
    </div>

    <div style="display:flex;width:100%">
      <div style="flex:1">Qty shelf:</div>
      <div style="flex:1">{{ qty_onshelf }}</div>
    </div>

    <div  style="display:flex; width:100%; padding:5px">
      
      <div style="flex:1"><input style="width:4em" type="text" ref="qty_on_shelf" v-model="adjusted_qty_onshelf" /></div>
      <div style="flex:2"><button :disabled="! product_id > 0" :class="{updated: updated}" @click="adjustItem">Actual shelf qty</button>&nbsp;</div>
    </div>


  </div>
</template>

<script>
export default {
  data() {
    return {
      
      barcode: null,
      product_code:null,
      product_find_key: null,
      qty_ordered: 0,
      qty_available: 0,
      qty_onshelf: 0,
      adjusted_qty_onshelf: 0,
      product_id: 0,
      updated: false,
      loaded: false,
      error: false,
      message: null
    }
  },
  methods: {
    barcodeInput(val){
      let input = val.toString().trim()
      // remove checksum digit if present

      this.barcode = input.substring(0,12)

      if(this.barcode.length == 12 && !this.loaded){
        this.findItem()
      } else {
        this.clearForm()
      }
    },
    productCodeInput(val){
      this.product_code = val.toString().trim()
    },
    focusOn(input){
      //console.log('focus is on ',input.name)
      if(this.loaded){
        this.clearForm()
        this.barcode = null
        this.product_code = null
        //this.$forceUpdate()
        //this.$nextTick(() => this.$forceUpdate())
      }
    },
   
    findItem() {
          
          let data = { 
              // _token: K9homes.csrfToken, 
              barcode: (this.barcode !== null && this.barcode.length == 12 ) ? this.barcode : null,
              product_code: this.product_code
          }

          this.$http.post(pageVar.url + '/find', data ).then( (response) => {
              // success callback
              // console.log(response);
              let product = response.body
              this.product_id = product.id
              this.qty_available = parseInt(product.qty_instock)
              this.qty_ordered = parseInt(product.ordered)
              // default shelf qty to the combo of both
              
              this.qty_onshelf = parseInt(product.qty_instock) + parseInt(product.ordered)
              this.adjusted_qty_onshelf = this.qty_onshelf

              if(this.product_code === null)  {
                this.product_code = product.product_code
              }
              if(this.barcode === null)  {
                this.barcode = product.barcode
              }   

              this.loaded = true 
              this.error = false  

              // Move focus
              this.$refs.qty_on_shelf.focus()      

          }, (response) => {
              // error callback
              this.clearForm();
              this.error = true;
              //alert('server error encountered');
          });
      },
      adjustItem(qty){
        
        let data = { 
              // _token: K9homes.csrfToken, 
              qty_instock: this.adjusted_qty_onshelf - this.qty_ordered
          }

          this.$http.post(pageVar.url + '/'+this.product_id, data ).then( (response) => {
              // success callback
              // console.log(response);
              let product = response.body
              this.product_id = product.id
              this.qty_available = parseInt(product.qty_instock)
              this.qty_ordered = parseInt(product.ordered)
              // default shelf qty to the combo of both
              this.qty_onshelf = this.qty_available + this.qty_ordered
              this.adjusted_qty_onshelf = this.qty_onshelf

              this.clearForm()
              this.barcode = null
              this.product_code = null
              this.$refs.barcode_input.focus()
              

          }, (response) => {
              // error callback
              this.clearForm();
              this.error = true;
              //alert('server error encountered');
          });
      },
      
      clearForm() {
        this.qty_available = 0
        this.qty_ordered = 0
        this.qty_onshelf = 0
        this.adjusted_qty_onshelf = 0
        // this.barcode = null
        // this.product_code = null
        this.product_id = 0

        this.updated = false
        this.loaded = false
        this.error = false

        this.$refs.barcode_input.focus()
      }
    
  },
  mounted() {
    
    this.$refs.barcode_input.focus()
    //this.$nextTick(() => this.$refs.user_input.focus())
    
  }
}

</script>

<style>
.updated {
  background: #ccffcc;
 
}
.loaded {
  background: #ccccff;

}
.error {
  background: #fcc;
}


</style>