
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

// Vue.filter('measured', {
//   // model -> view
//   // formats the value when updating the input element.
//   read: function(val) {
//     return val.toFixed(2)
//   },
//   // view -> model
//   // formats the value when updating the data.
//   write: function(val, oldVal) {
//     var number = +val.replace(/[^\d.]/g, '')
//     return isNaN(number) ? 0 : number
//   }
// })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.config.devtools = true;

//Vue.component('Clientquotes', require('./components/Clientquotes.vue'));

const app  = new Vue({
    el: '#app',
    data: {
    	
    	client: '',
        location: '',
    	postcode: '',
    	suburb: '',
    	statecode: '',
        locationValid: false,
    	newPackage:{},
    	packages: [],
    	companies: {},
    	lowestTotalQuote: {},
    	lowestPackageQuote: {},
    	quotes: [], //{ company: 'Big freight Co', method: 'Fastex', cost: 456 },
    	inputMessage: ''
    },
    computed: {
    	actualWeight: function() {
    		return _.sumBy(this.packages, function(o) {
    			let weight = parseFloat(o['weight'])
    			if (! isNaN(weight) ) {
    				return weight
    			}
    		})
    	},
    	cubicWeight: function() {
    		return _.sumBy(this.packages, function(o) {
    			let volume = (parseFloat(o['length']) * parseFloat(o['width']) * parseFloat(o['height']) ) * 250/1000000
    			if (! isNaN(volume) ) {
    				return volume
    			}	
    		})
    	},



    },
    methods: {
    	quoteTotal: function(quotes) {
    		//console.log(quotes[1].cost)
    		// This function gets called at the end
    		// of each table section for company-method-package_quotes list
    		// So here is a goo point to check if
    		// the current quote is a complete quote for all
    		// packages and if so is it the lowest all-up cost
    		// Also check if the quote for each package is the
    		// lowest quote for that particular package

    		// Current lowestTotalQuote
    		var currentQuoteTotal = _.chain(quotes)
			    					.map('cost')
			    					.sum()
			    					.value()


    		//var currentQuoteTotal =  _.sum(_.map(quotes,'cost'))

    		// Check if current quote covers all packages
    		var allPackages = _.every(this.packages, function(p){
    			return _.find(quotes, function(o){
    				return o.package_id == p.id
    			} )
    		})
    		console.log('All Packages', allPackages)

    		return currentQuoteTotal.toFixed(2)
		  
    	},
    	companyQuotes: function(companyKey){
    		// find all quotes with companyKey
    		let quotes = _.find(this.quotes, function(quote){
    			return quote.company == companyKey
    		})
    		console.log(companyKey, quotes)
    		return quotes

    	},
    	formKeyup: function() {
    		this.inputMessage = ''
    	},
    	addPackage: function () {

    		//console.log(this.newPackage['length'])
    		if(
    			(typeof this.newPackage['length'] !== 'undefined') && (parseFloat(this.newPackage['length']) > 0 ) &&
    			(typeof this.newPackage['width'] !== 'undefined') && (parseFloat(this.newPackage['width']) > 0 ) &&
    			(typeof this.newPackage['height'] !== 'undefined') && (parseFloat(this.newPackage['height']) > 0 ) &&
    			(typeof this.newPackage['weight'] !== 'undefined') && (parseFloat(this.newPackage['weight']) > 0 )
    		) {
    			this.newPackage.id = this.packages.length + 1 
    			this.packages.push(this.newPackage)
	      		this.newPackage = {};

	      		

    		} else {
    			//alert ("package data entered is not valid")
    			this.inputMessage = 'Invalid input, try again ...'
    		}
    		
	      	
    	},
        getQuotes: function() {
            // make remote call - just to test - use get quotes button me thinks
            // If location is set
            // AND
            // Package count > 0
            if(this.locationValid && this.packages.length > 0){
                // make the remote call to get the quotes
                this.remote({
                    packages: this.packages,
                    postcode: this.postcode,
                    suburb: this.suburb,
                    statecode: this.statecode
                });
            }

                
        },

    	packageData: function(item) {
    		return 'L: ' + item['length'] +  ' W: ' + item['width'] + ' H: ' + item['height'] + ' Kg: ' + item['weight'];
    	},
    	
	    remote(data){
	    	let url = '/freight/quote';
			return this.$http.post(url, data).then((response) => {
			// success callback
			//console.log(response.body.quotes);
			let quotes = response.body.quotes;

			// for every company/method/quotes list
			// total quote list cost and assig to
			// company/method/total
			var self = this
			 _.each(quotes, function(q) {
				_.each(q, function(fmethod, fcompany) {
					fmethod['total'] = _.chain(fmethod.quotes)
										.map('cost')
										.sum()
										.value()

					var package_ids = _.map(self.packages,'id')
					var quote_package_ids = 	_.chain(fmethod.quotes)
												.map('package_id')
												.value()

					// console.log('pids', package_ids)

					// console.log('qpids', quote_package_ids)

					fmethod['missed_packages'] = _.difference(package_ids,quote_package_ids)

					
				})
			})

			this.companies = response.body.companies;
			this.quotes = quotes;
			// _.each(quotes, function(quote,company){
			// 	console.log(quote)
			//     this.quotes.push(quote);
			// }.bind(this));

			}, (response) => {
			// error callback
			//console.log(response);
			});
	    },
        querySearchAsync(queryString, cb) {
        //let queryStringLength = typeof queryString.length !== 'undefned' ? queryString.length : 0
        //console.log(queryString.length);
        if( queryString.length < 3 ){
             cb([{'value':'min three characters'}])
             return;
        }

       let url = '/freight/location?postcode=' + queryString
            return this.$http.get(url).then((response) => {
            // success callback
            // console.log(response.body);
            let options = response.body;
            cb(options)

            }, (response) => {
            // error callback
            //console.log(response);
            });
      },
      handleLocationSelect(item) {
        // value ='postcode, suburb, state'
        let loc = item.value.split(',')
        this.postcode = loc[0].trim()
        this.suburb = loc[1].trim()
        this.statecode = loc[2].trim()

        if (    this.postcode.length > 2 &&
                this.suburb.length > 3 &&
                this.statecode.length > 1 ){
            // set locationValid
            this.locationValid = true
        }

        // clear out quotes as they could be invalid
        this.quotes = []
      },

     
	    
      lookupClientAsync(queryString, cb) {
      	//let queryStringLength = typeof queryString.length !== 'undefned' ? queryString.length : 0
      	//console.log(queryString.length);
      	if( queryString.length < 3 ){
      		 cb([{'value':'min three characters'}])
      		 return;
      	}
         
            let url = '/clientlookup?q=' + queryString
			return this.$http.get(url).then((response) => {
			// success callback
			console.log(response.body);
			let results = response.body;

            let options = _.map(results, function(r) {
                return { value: r.name + ', ' + r.postcode + ', ' + r.city  + ', ' + r.state }
            })

            console.log(options)
			cb(options)

			}, (response) => {
			// error callback
			//console.log(response);
			});
      },
      handleClientSelect(item) {
        // value ='postcode, suburb, state'
        let loc = item.value.split(',')
        this.postcode = loc[1].trim()
        this.suburb = loc[2].trim()
        this.statecode = loc[3].trim()

        if (    this.postcode.length > 2 &&
                this.suburb.length > 3 &&
                this.statecode.length > 1 ){
            // set locationValid
            this.locationValid = true
        }

        // clear out quotes as they could be invalid
        this.quotes = []
      }

    }, // end of methods
    

    
});