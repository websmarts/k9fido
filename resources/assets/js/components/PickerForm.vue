<template>
    <form class="pickerform">
    <fieldset>
        <h2>Pick Order: {{orderId}}</h2>
        <template v-for="item in items">

            <item :item="item" v-on:picked="focusNextItem"></item>
            
        </template>
        <div class="buttons">
        <a class="btn btn-success pull-right" v-on:click="parkOrder">Park Order</a>
        <a class="btn btn-primary pull-left" v-on:click="saveOrder">Save Order</a>
        </div>
        </fieldset>
    </form>
                
</template>

<script>
    export default {
        props: ['orderId'],
        data() {
          return {
                items: []
            };
        },
        components: {
            item: require('./pickerItem.vue')
        },
        methods: {

            parkOrder() {
                this.saveItems('parked');
            },
            saveOrder() {
                console.log('Save the order');
                this.saveItems('picked');
            },
            saveItems(status) {
                let data = { 
                    _token: K9homes.csrfToken, 
                    status: status, 
                    order_id: pageVar.orderId, 
                    items: this.items 
                }

                this.$http.post(pageVar.url, data ).then( (response) => {
                    // success callback
                    // console.log(response);
                    // If soft error then Alert user
                  if(response.body.status == 'success'){
                     window.location = response.body.location;
                  }

                  if(response.body.status == 'error'){
                    alert('Error encountered');
                  }

                }, (response) => {
                    // error callback
                    alert('server error encountered');
                });
            },
            focusNextItem() {
                // Find the next item that has not been picked and give it focus.
                _.forEach(this.items, function(item){
                    if(item.qty != (item.qty_supplied + item.picked_qty)){
                        $('#item_'+item.id).focus();
                        return false;
                    }
                });
            },
            handleResize() {
                alert('window resized');
            }



              

        },
        
        created() {
          this.$http.get(pageVar.url).then( (response) => {
            // Success callback
            _.forEach(response.body.data, (value) => {
                value.scanned_barcode = '';
                value.status = "incomplete";
                value.input ='';
                value.picked_qty ='';
                value.error = false;
                this.items.push(value);
            });

            

          }, (response) => {
            // Error callback
          }) 
        },
        mounted() {
            
        },
        destroy() {
            
        }
    }
</script>

<style>
.pickerform fieldset {
    border: 1px solid #888;
    padding: 8px;
    border-radius: 10px;
    background: #eee;
}
.pickerform span {
    color: #555;
    font-size: 80%;
}
.pickerform div.item {
    border-left: 8px solid orange;
}
.pickerform div.item.complete {
    border-left: 8px solid #999;
}
.pickerform input.input {
    width: 9em;
    font-size: 16px;
}
.pickerform h2 {
    text-align: center;
    color: #666;
    margin-top:5px;
}
.pickerform h3 {
    margin: 0;
    color: #000;
    font-size: 100%;

}
.pickerform h4 {
    margin: 0;
    font-size: 100%;
    color: #444;
    font-weight: normal;
}
.pickerform .item {
    margin-bottom: 5px;
    padding-left:3px;
    border-bottom: 5px solid #fff;
}
.buttons {
    margin-top: 15px;
}

</style>
