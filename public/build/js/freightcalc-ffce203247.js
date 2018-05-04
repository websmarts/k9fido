/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmory imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmory exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		Object.defineProperty(exports, name, {
/******/ 			configurable: false,
/******/ 			enumerable: true,
/******/ 			get: getter
/******/ 		});
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

eval("\r\n/**\r\n * First we will load all of this project's JavaScript dependencies which\r\n * include Vue and Vue Resource. This gives a great starting point for\r\n * building robust, powerful web applications using Vue and Laravel.\r\n */\r\n\r\n// Vue.filter('measured', {\r\n//   // model -> view\r\n//   // formats the value when updating the input element.\r\n//   read: function(val) {\r\n//     return val.toFixed(2)\r\n//   },\r\n//   // view -> model\r\n//   // formats the value when updating the data.\r\n//   write: function(val, oldVal) {\r\n//     var number = +val.replace(/[^\\d.]/g, '')\r\n//     return isNaN(number) ? 0 : number\r\n//   }\r\n// })\r\n\r\n/**\r\n * Next, we will create a fresh Vue application instance and attach it to\r\n * the body of the page. From here, you may begin adding components to\r\n * the application, or feel free to tweak this setup for your needs.\r\n */\r\n\r\n// Vue.config.devtools = true;\r\n\r\n//Vue.component('Clientquotes', require('./components/Clientquotes.vue'));\r\n\r\nvar app  = new Vue({\r\n    el: '#app',\r\n    data: {\r\n    \t\r\n    \tclient: '',\r\n        location: '',\r\n    \tpostcode: '',\r\n    \tsuburb: '',\r\n    \tstatecode: '',\r\n        locationValid: false,\r\n    \tnewPackage:{},\r\n    \tpackages: [],\r\n    \tcompanies: {},\r\n    \tlowestTotalQuote: {},\r\n    \tlowestPackageQuote: {},\r\n    \tquotes: [], //{ company: 'Big freight Co', method: 'Fastex', cost: 456 },\r\n    \tinputMessage: ''\r\n    },\r\n    computed: {\r\n    \tactualWeight: function() {\r\n    \t\treturn _.sumBy(this.packages, function(o) {\r\n    \t\t\tvar weight = parseFloat(o['weight'])\r\n    \t\t\tif (! isNaN(weight) ) {\r\n    \t\t\t\treturn weight\r\n    \t\t\t}\r\n    \t\t})\r\n    \t},\r\n    \tcubicWeight: function() {\r\n    \t\treturn _.sumBy(this.packages, function(o) {\r\n    \t\t\tvar volume = (parseFloat(o['length']) * parseFloat(o['width']) * parseFloat(o['height']) ) * 250/1000000\r\n    \t\t\tif (! isNaN(volume) ) {\r\n    \t\t\t\treturn volume\r\n    \t\t\t}\t\r\n    \t\t})\r\n    \t},\r\n\r\n\r\n\r\n    },\r\n    methods: {\r\n    \tquoteTotal: function(quotes) {\r\n    \t\t//console.log(quotes[1].cost)\r\n    \t\t// This function gets called at the end\r\n    \t\t// of each table section for company-method-package_quotes list\r\n    \t\t// So here is a goo point to check if\r\n    \t\t// the current quote is a complete quote for all\r\n    \t\t// packages and if so is it the lowest all-up cost\r\n    \t\t// Also check if the quote for each package is the\r\n    \t\t// lowest quote for that particular package\r\n\r\n    \t\t// Current lowestTotalQuote\r\n    \t\tvar currentQuoteTotal = _.chain(quotes)\r\n\t\t\t    \t\t\t\t\t.map('cost')\r\n\t\t\t    \t\t\t\t\t.sum()\r\n\t\t\t    \t\t\t\t\t.value()\r\n\r\n\r\n    \t\t//var currentQuoteTotal =  _.sum(_.map(quotes,'cost'))\r\n\r\n    \t\t// Check if current quote covers all packages\r\n    \t\tvar allPackages = _.every(this.packages, function(p){\r\n    \t\t\treturn _.find(quotes, function(o){\r\n    \t\t\t\treturn o.package_id == p.id\r\n    \t\t\t} )\r\n    \t\t})\r\n    \t\tconsole.log('All Packages', allPackages)\r\n\r\n    \t\treturn currentQuoteTotal.toFixed(2)\r\n\t\t  \r\n    \t},\r\n    \tcompanyQuotes: function(companyKey){\r\n    \t\t// find all quotes with companyKey\r\n    \t\tvar quotes = _.find(this.quotes, function(quote){\r\n    \t\t\treturn quote.company == companyKey\r\n    \t\t})\r\n    \t\tconsole.log(companyKey, quotes)\r\n    \t\treturn quotes\r\n\r\n    \t},\r\n    \tformKeyup: function() {\r\n    \t\tthis.inputMessage = ''\r\n    \t},\r\n    \taddPackage: function () {\r\n\r\n    \t\t//console.log(this.newPackage['length'])\r\n    \t\tif(\r\n    \t\t\t(typeof this.newPackage['length'] !== 'undefined') && (parseFloat(this.newPackage['length']) > 0 ) &&\r\n    \t\t\t(typeof this.newPackage['width'] !== 'undefined') && (parseFloat(this.newPackage['width']) > 0 ) &&\r\n    \t\t\t(typeof this.newPackage['height'] !== 'undefined') && (parseFloat(this.newPackage['height']) > 0 ) &&\r\n    \t\t\t(typeof this.newPackage['weight'] !== 'undefined') && (parseFloat(this.newPackage['weight']) > 0 )\r\n    \t\t) {\r\n    \t\t\tthis.newPackage.id = this.packages.length + 1 \r\n    \t\t\tthis.packages.push(this.newPackage)\r\n\t      \t\tthis.newPackage = {};\r\n\r\n\t      \t\t\r\n\r\n    \t\t} else {\r\n    \t\t\t//alert (\"package data entered is not valid\")\r\n    \t\t\tthis.inputMessage = 'Invalid input, try again ...'\r\n    \t\t}\r\n    \t\t\r\n\t      \t\r\n    \t},\r\n        getQuotes: function() {\r\n            // make remote call - just to test - use get quotes button me thinks\r\n            // If location is set\r\n            // AND\r\n            // Package count > 0\r\n            if(this.locationValid && this.packages.length > 0){\r\n                // make the remote call to get the quotes\r\n                this.remote({\r\n                    packages: this.packages,\r\n                    postcode: this.postcode,\r\n                    suburb: this.suburb,\r\n                    statecode: this.statecode\r\n                });\r\n            }\r\n\r\n                \r\n        },\r\n\r\n    \tpackageData: function(item) {\r\n    \t\treturn 'L: ' + item['length'] +  ' W: ' + item['width'] + ' H: ' + item['height'] + ' Kg: ' + item['weight'];\r\n    \t},\r\n    \t\r\n\t    remote: function remote(data){\n\t    \tvar this$1 = this;\n\r\n\t    \tvar url = '/freight/quote';\r\n\t\t\treturn this.$http.post(url, data).then(function (response) {\r\n\t\t\t// success callback\r\n\t\t\t//console.log(response.body.quotes);\r\n\t\t\tvar quotes = response.body.quotes;\r\n\r\n\t\t\t// for every company/method/quotes list\r\n\t\t\t// total quote list cost and assig to\r\n\t\t\t// company/method/total\r\n\t\t\tvar self = this$1\r\n\t\t\t _.each(quotes, function(q) {\r\n\t\t\t\t_.each(q, function(fmethod, fcompany) {\r\n\t\t\t\t\tfmethod['total'] = _.chain(fmethod.quotes)\r\n\t\t\t\t\t\t\t\t\t\t.map('cost')\r\n\t\t\t\t\t\t\t\t\t\t.sum()\r\n\t\t\t\t\t\t\t\t\t\t.value()\r\n\r\n\t\t\t\t\tvar package_ids = _.map(self.packages,'id')\r\n\t\t\t\t\tvar quote_package_ids = \t_.chain(fmethod.quotes)\r\n\t\t\t\t\t\t\t\t\t\t\t\t.map('package_id')\r\n\t\t\t\t\t\t\t\t\t\t\t\t.value()\r\n\r\n\t\t\t\t\t// console.log('pids', package_ids)\r\n\r\n\t\t\t\t\t// console.log('qpids', quote_package_ids)\r\n\r\n\t\t\t\t\tfmethod['missed_packages'] = _.difference(package_ids,quote_package_ids)\r\n\r\n\t\t\t\t\t\r\n\t\t\t\t})\r\n\t\t\t})\r\n\r\n\t\t\tthis$1.companies = response.body.companies;\r\n\t\t\tthis$1.quotes = quotes;\r\n\t\t\t// _.each(quotes, function(quote,company){\r\n\t\t\t// \tconsole.log(quote)\r\n\t\t\t//     this.quotes.push(quote);\r\n\t\t\t// }.bind(this));\r\n\r\n\t\t\t}, function (response) {\r\n\t\t\t// error callback\r\n\t\t\t//console.log(response);\r\n\t\t\t});\r\n\t    },\r\n        querySearchAsync: function querySearchAsync(queryString, cb) {\r\n        //let queryStringLength = typeof queryString.length !== 'undefned' ? queryString.length : 0\r\n        //console.log(queryString.length);\r\n        if( queryString.length < 3 ){\r\n             cb([{'value':'min three characters'}])\r\n             return;\r\n        }\r\n\r\n       var url = '/freight/location?postcode=' + queryString\r\n            return this.$http.get(url).then(function (response) {\r\n            // success callback\r\n            // console.log(response.body);\r\n            var options = response.body;\r\n            cb(options)\r\n\r\n            }, function (response) {\r\n            // error callback\r\n            //console.log(response);\r\n            });\r\n      },\r\n      handleLocationSelect: function handleLocationSelect(item) {\r\n        // value ='postcode, suburb, state'\r\n        var loc = item.value.split(',')\r\n        this.postcode = loc[0].trim()\r\n        this.suburb = loc[1].trim()\r\n        this.statecode = loc[2].trim()\r\n\r\n        if (    this.postcode.length > 2 &&\r\n                this.suburb.length > 3 &&\r\n                this.statecode.length > 1 ){\r\n            // set locationValid\r\n            this.locationValid = true\r\n        }\r\n\r\n        // clear out quotes as they could be invalid\r\n        this.quotes = []\r\n      },\r\n\r\n     \r\n\t    \r\n      lookupClientAsync: function lookupClientAsync(queryString, cb) {\r\n      \t//let queryStringLength = typeof queryString.length !== 'undefned' ? queryString.length : 0\r\n      \t//console.log(queryString.length);\r\n      \tif( queryString.length < 3 ){\r\n      \t\t cb([{'value':'min three characters'}])\r\n      \t\t return;\r\n      \t}\r\n         \r\n            var url = '/clientlookup?q=' + queryString\r\n\t\t\treturn this.$http.get(url).then(function (response) {\r\n\t\t\t// success callback\r\n\t\t\tconsole.log(response.body);\r\n\t\t\tvar results = response.body;\r\n\r\n            var options = _.map(results, function(r) {\r\n                return { value: r.name + ', ' + r.postcode + ', ' + r.city  + ', ' + r.state }\r\n            })\r\n\r\n            console.log(options)\r\n\t\t\tcb(options)\r\n\r\n\t\t\t}, function (response) {\r\n\t\t\t// error callback\r\n\t\t\t//console.log(response);\r\n\t\t\t});\r\n      },\r\n      handleClientSelect: function handleClientSelect(item) {\r\n        // value ='postcode, suburb, state'\r\n        var loc = item.value.split(',')\r\n        this.postcode = loc[1].trim()\r\n        this.suburb = loc[2].trim()\r\n        this.statecode = loc[3].trim()\r\n\r\n        if (    this.postcode.length > 2 &&\r\n                this.suburb.length > 3 &&\r\n                this.statecode.length > 1 ){\r\n            // set locationValid\r\n            this.locationValid = true\r\n        }\r\n\r\n        // clear out quotes as they could be invalid\r\n        this.quotes = []\r\n      }\r\n\r\n    }, // end of methods\r\n    \r\n\r\n    \r\n});//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2ZyZWlnaHRjYWxjLmpzPzI3ZTIiXSwic291cmNlc0NvbnRlbnQiOlsiXHJcbi8qKlxyXG4gKiBGaXJzdCB3ZSB3aWxsIGxvYWQgYWxsIG9mIHRoaXMgcHJvamVjdCdzIEphdmFTY3JpcHQgZGVwZW5kZW5jaWVzIHdoaWNoXHJcbiAqIGluY2x1ZGUgVnVlIGFuZCBWdWUgUmVzb3VyY2UuIFRoaXMgZ2l2ZXMgYSBncmVhdCBzdGFydGluZyBwb2ludCBmb3JcclxuICogYnVpbGRpbmcgcm9idXN0LCBwb3dlcmZ1bCB3ZWIgYXBwbGljYXRpb25zIHVzaW5nIFZ1ZSBhbmQgTGFyYXZlbC5cclxuICovXHJcblxyXG4vLyBWdWUuZmlsdGVyKCdtZWFzdXJlZCcsIHtcclxuLy8gICAvLyBtb2RlbCAtPiB2aWV3XHJcbi8vICAgLy8gZm9ybWF0cyB0aGUgdmFsdWUgd2hlbiB1cGRhdGluZyB0aGUgaW5wdXQgZWxlbWVudC5cclxuLy8gICByZWFkOiBmdW5jdGlvbih2YWwpIHtcclxuLy8gICAgIHJldHVybiB2YWwudG9GaXhlZCgyKVxyXG4vLyAgIH0sXHJcbi8vICAgLy8gdmlldyAtPiBtb2RlbFxyXG4vLyAgIC8vIGZvcm1hdHMgdGhlIHZhbHVlIHdoZW4gdXBkYXRpbmcgdGhlIGRhdGEuXHJcbi8vICAgd3JpdGU6IGZ1bmN0aW9uKHZhbCwgb2xkVmFsKSB7XHJcbi8vICAgICB2YXIgbnVtYmVyID0gK3ZhbC5yZXBsYWNlKC9bXlxcZC5dL2csICcnKVxyXG4vLyAgICAgcmV0dXJuIGlzTmFOKG51bWJlcikgPyAwIDogbnVtYmVyXHJcbi8vICAgfVxyXG4vLyB9KVxyXG5cclxuLyoqXHJcbiAqIE5leHQsIHdlIHdpbGwgY3JlYXRlIGEgZnJlc2ggVnVlIGFwcGxpY2F0aW9uIGluc3RhbmNlIGFuZCBhdHRhY2ggaXQgdG9cclxuICogdGhlIGJvZHkgb2YgdGhlIHBhZ2UuIEZyb20gaGVyZSwgeW91IG1heSBiZWdpbiBhZGRpbmcgY29tcG9uZW50cyB0b1xyXG4gKiB0aGUgYXBwbGljYXRpb24sIG9yIGZlZWwgZnJlZSB0byB0d2VhayB0aGlzIHNldHVwIGZvciB5b3VyIG5lZWRzLlxyXG4gKi9cclxuXHJcbi8vIFZ1ZS5jb25maWcuZGV2dG9vbHMgPSB0cnVlO1xyXG5cclxuLy9WdWUuY29tcG9uZW50KCdDbGllbnRxdW90ZXMnLCByZXF1aXJlKCcuL2NvbXBvbmVudHMvQ2xpZW50cXVvdGVzLnZ1ZScpKTtcclxuXHJcbmNvbnN0IGFwcCAgPSBuZXcgVnVlKHtcclxuICAgIGVsOiAnI2FwcCcsXHJcbiAgICBkYXRhOiB7XHJcbiAgICBcdFxyXG4gICAgXHRjbGllbnQ6ICcnLFxyXG4gICAgICAgIGxvY2F0aW9uOiAnJyxcclxuICAgIFx0cG9zdGNvZGU6ICcnLFxyXG4gICAgXHRzdWJ1cmI6ICcnLFxyXG4gICAgXHRzdGF0ZWNvZGU6ICcnLFxyXG4gICAgICAgIGxvY2F0aW9uVmFsaWQ6IGZhbHNlLFxyXG4gICAgXHRuZXdQYWNrYWdlOnt9LFxyXG4gICAgXHRwYWNrYWdlczogW10sXHJcbiAgICBcdGNvbXBhbmllczoge30sXHJcbiAgICBcdGxvd2VzdFRvdGFsUXVvdGU6IHt9LFxyXG4gICAgXHRsb3dlc3RQYWNrYWdlUXVvdGU6IHt9LFxyXG4gICAgXHRxdW90ZXM6IFtdLCAvL3sgY29tcGFueTogJ0JpZyBmcmVpZ2h0IENvJywgbWV0aG9kOiAnRmFzdGV4JywgY29zdDogNDU2IH0sXHJcbiAgICBcdGlucHV0TWVzc2FnZTogJydcclxuICAgIH0sXHJcbiAgICBjb21wdXRlZDoge1xyXG4gICAgXHRhY3R1YWxXZWlnaHQ6IGZ1bmN0aW9uKCkge1xyXG4gICAgXHRcdHJldHVybiBfLnN1bUJ5KHRoaXMucGFja2FnZXMsIGZ1bmN0aW9uKG8pIHtcclxuICAgIFx0XHRcdGxldCB3ZWlnaHQgPSBwYXJzZUZsb2F0KG9bJ3dlaWdodCddKVxyXG4gICAgXHRcdFx0aWYgKCEgaXNOYU4od2VpZ2h0KSApIHtcclxuICAgIFx0XHRcdFx0cmV0dXJuIHdlaWdodFxyXG4gICAgXHRcdFx0fVxyXG4gICAgXHRcdH0pXHJcbiAgICBcdH0sXHJcbiAgICBcdGN1YmljV2VpZ2h0OiBmdW5jdGlvbigpIHtcclxuICAgIFx0XHRyZXR1cm4gXy5zdW1CeSh0aGlzLnBhY2thZ2VzLCBmdW5jdGlvbihvKSB7XHJcbiAgICBcdFx0XHRsZXQgdm9sdW1lID0gKHBhcnNlRmxvYXQob1snbGVuZ3RoJ10pICogcGFyc2VGbG9hdChvWyd3aWR0aCddKSAqIHBhcnNlRmxvYXQob1snaGVpZ2h0J10pICkgKiAyNTAvMTAwMDAwMFxyXG4gICAgXHRcdFx0aWYgKCEgaXNOYU4odm9sdW1lKSApIHtcclxuICAgIFx0XHRcdFx0cmV0dXJuIHZvbHVtZVxyXG4gICAgXHRcdFx0fVx0XHJcbiAgICBcdFx0fSlcclxuICAgIFx0fSxcclxuXHJcblxyXG5cclxuICAgIH0sXHJcbiAgICBtZXRob2RzOiB7XHJcbiAgICBcdHF1b3RlVG90YWw6IGZ1bmN0aW9uKHF1b3Rlcykge1xyXG4gICAgXHRcdC8vY29uc29sZS5sb2cocXVvdGVzWzFdLmNvc3QpXHJcbiAgICBcdFx0Ly8gVGhpcyBmdW5jdGlvbiBnZXRzIGNhbGxlZCBhdCB0aGUgZW5kXHJcbiAgICBcdFx0Ly8gb2YgZWFjaCB0YWJsZSBzZWN0aW9uIGZvciBjb21wYW55LW1ldGhvZC1wYWNrYWdlX3F1b3RlcyBsaXN0XHJcbiAgICBcdFx0Ly8gU28gaGVyZSBpcyBhIGdvbyBwb2ludCB0byBjaGVjayBpZlxyXG4gICAgXHRcdC8vIHRoZSBjdXJyZW50IHF1b3RlIGlzIGEgY29tcGxldGUgcXVvdGUgZm9yIGFsbFxyXG4gICAgXHRcdC8vIHBhY2thZ2VzIGFuZCBpZiBzbyBpcyBpdCB0aGUgbG93ZXN0IGFsbC11cCBjb3N0XHJcbiAgICBcdFx0Ly8gQWxzbyBjaGVjayBpZiB0aGUgcXVvdGUgZm9yIGVhY2ggcGFja2FnZSBpcyB0aGVcclxuICAgIFx0XHQvLyBsb3dlc3QgcXVvdGUgZm9yIHRoYXQgcGFydGljdWxhciBwYWNrYWdlXHJcblxyXG4gICAgXHRcdC8vIEN1cnJlbnQgbG93ZXN0VG90YWxRdW90ZVxyXG4gICAgXHRcdHZhciBjdXJyZW50UXVvdGVUb3RhbCA9IF8uY2hhaW4ocXVvdGVzKVxyXG5cdFx0XHQgICAgXHRcdFx0XHRcdC5tYXAoJ2Nvc3QnKVxyXG5cdFx0XHQgICAgXHRcdFx0XHRcdC5zdW0oKVxyXG5cdFx0XHQgICAgXHRcdFx0XHRcdC52YWx1ZSgpXHJcblxyXG5cclxuICAgIFx0XHQvL3ZhciBjdXJyZW50UXVvdGVUb3RhbCA9ICBfLnN1bShfLm1hcChxdW90ZXMsJ2Nvc3QnKSlcclxuXHJcbiAgICBcdFx0Ly8gQ2hlY2sgaWYgY3VycmVudCBxdW90ZSBjb3ZlcnMgYWxsIHBhY2thZ2VzXHJcbiAgICBcdFx0dmFyIGFsbFBhY2thZ2VzID0gXy5ldmVyeSh0aGlzLnBhY2thZ2VzLCBmdW5jdGlvbihwKXtcclxuICAgIFx0XHRcdHJldHVybiBfLmZpbmQocXVvdGVzLCBmdW5jdGlvbihvKXtcclxuICAgIFx0XHRcdFx0cmV0dXJuIG8ucGFja2FnZV9pZCA9PSBwLmlkXHJcbiAgICBcdFx0XHR9IClcclxuICAgIFx0XHR9KVxyXG4gICAgXHRcdGNvbnNvbGUubG9nKCdBbGwgUGFja2FnZXMnLCBhbGxQYWNrYWdlcylcclxuXHJcbiAgICBcdFx0cmV0dXJuIGN1cnJlbnRRdW90ZVRvdGFsLnRvRml4ZWQoMilcclxuXHRcdCAgXHJcbiAgICBcdH0sXHJcbiAgICBcdGNvbXBhbnlRdW90ZXM6IGZ1bmN0aW9uKGNvbXBhbnlLZXkpe1xyXG4gICAgXHRcdC8vIGZpbmQgYWxsIHF1b3RlcyB3aXRoIGNvbXBhbnlLZXlcclxuICAgIFx0XHRsZXQgcXVvdGVzID0gXy5maW5kKHRoaXMucXVvdGVzLCBmdW5jdGlvbihxdW90ZSl7XHJcbiAgICBcdFx0XHRyZXR1cm4gcXVvdGUuY29tcGFueSA9PSBjb21wYW55S2V5XHJcbiAgICBcdFx0fSlcclxuICAgIFx0XHRjb25zb2xlLmxvZyhjb21wYW55S2V5LCBxdW90ZXMpXHJcbiAgICBcdFx0cmV0dXJuIHF1b3Rlc1xyXG5cclxuICAgIFx0fSxcclxuICAgIFx0Zm9ybUtleXVwOiBmdW5jdGlvbigpIHtcclxuICAgIFx0XHR0aGlzLmlucHV0TWVzc2FnZSA9ICcnXHJcbiAgICBcdH0sXHJcbiAgICBcdGFkZFBhY2thZ2U6IGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICBcdFx0Ly9jb25zb2xlLmxvZyh0aGlzLm5ld1BhY2thZ2VbJ2xlbmd0aCddKVxyXG4gICAgXHRcdGlmKFxyXG4gICAgXHRcdFx0KHR5cGVvZiB0aGlzLm5ld1BhY2thZ2VbJ2xlbmd0aCddICE9PSAndW5kZWZpbmVkJykgJiYgKHBhcnNlRmxvYXQodGhpcy5uZXdQYWNrYWdlWydsZW5ndGgnXSkgPiAwICkgJiZcclxuICAgIFx0XHRcdCh0eXBlb2YgdGhpcy5uZXdQYWNrYWdlWyd3aWR0aCddICE9PSAndW5kZWZpbmVkJykgJiYgKHBhcnNlRmxvYXQodGhpcy5uZXdQYWNrYWdlWyd3aWR0aCddKSA+IDAgKSAmJlxyXG4gICAgXHRcdFx0KHR5cGVvZiB0aGlzLm5ld1BhY2thZ2VbJ2hlaWdodCddICE9PSAndW5kZWZpbmVkJykgJiYgKHBhcnNlRmxvYXQodGhpcy5uZXdQYWNrYWdlWydoZWlnaHQnXSkgPiAwICkgJiZcclxuICAgIFx0XHRcdCh0eXBlb2YgdGhpcy5uZXdQYWNrYWdlWyd3ZWlnaHQnXSAhPT0gJ3VuZGVmaW5lZCcpICYmIChwYXJzZUZsb2F0KHRoaXMubmV3UGFja2FnZVsnd2VpZ2h0J10pID4gMCApXHJcbiAgICBcdFx0KSB7XHJcbiAgICBcdFx0XHR0aGlzLm5ld1BhY2thZ2UuaWQgPSB0aGlzLnBhY2thZ2VzLmxlbmd0aCArIDEgXHJcbiAgICBcdFx0XHR0aGlzLnBhY2thZ2VzLnB1c2godGhpcy5uZXdQYWNrYWdlKVxyXG5cdCAgICAgIFx0XHR0aGlzLm5ld1BhY2thZ2UgPSB7fTtcclxuXHJcblx0ICAgICAgXHRcdFxyXG5cclxuICAgIFx0XHR9IGVsc2Uge1xyXG4gICAgXHRcdFx0Ly9hbGVydCAoXCJwYWNrYWdlIGRhdGEgZW50ZXJlZCBpcyBub3QgdmFsaWRcIilcclxuICAgIFx0XHRcdHRoaXMuaW5wdXRNZXNzYWdlID0gJ0ludmFsaWQgaW5wdXQsIHRyeSBhZ2FpbiAuLi4nXHJcbiAgICBcdFx0fVxyXG4gICAgXHRcdFxyXG5cdCAgICAgIFx0XHJcbiAgICBcdH0sXHJcbiAgICAgICAgZ2V0UXVvdGVzOiBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgLy8gbWFrZSByZW1vdGUgY2FsbCAtIGp1c3QgdG8gdGVzdCAtIHVzZSBnZXQgcXVvdGVzIGJ1dHRvbiBtZSB0aGlua3NcclxuICAgICAgICAgICAgLy8gSWYgbG9jYXRpb24gaXMgc2V0XHJcbiAgICAgICAgICAgIC8vIEFORFxyXG4gICAgICAgICAgICAvLyBQYWNrYWdlIGNvdW50ID4gMFxyXG4gICAgICAgICAgICBpZih0aGlzLmxvY2F0aW9uVmFsaWQgJiYgdGhpcy5wYWNrYWdlcy5sZW5ndGggPiAwKXtcclxuICAgICAgICAgICAgICAgIC8vIG1ha2UgdGhlIHJlbW90ZSBjYWxsIHRvIGdldCB0aGUgcXVvdGVzXHJcbiAgICAgICAgICAgICAgICB0aGlzLnJlbW90ZSh7XHJcbiAgICAgICAgICAgICAgICAgICAgcGFja2FnZXM6IHRoaXMucGFja2FnZXMsXHJcbiAgICAgICAgICAgICAgICAgICAgcG9zdGNvZGU6IHRoaXMucG9zdGNvZGUsXHJcbiAgICAgICAgICAgICAgICAgICAgc3VidXJiOiB0aGlzLnN1YnVyYixcclxuICAgICAgICAgICAgICAgICAgICBzdGF0ZWNvZGU6IHRoaXMuc3RhdGVjb2RlXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIFxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgXHRwYWNrYWdlRGF0YTogZnVuY3Rpb24oaXRlbSkge1xyXG4gICAgXHRcdHJldHVybiAnTDogJyArIGl0ZW1bJ2xlbmd0aCddICsgICcgVzogJyArIGl0ZW1bJ3dpZHRoJ10gKyAnIEg6ICcgKyBpdGVtWydoZWlnaHQnXSArICcgS2c6ICcgKyBpdGVtWyd3ZWlnaHQnXTtcclxuICAgIFx0fSxcclxuICAgIFx0XHJcblx0ICAgIHJlbW90ZShkYXRhKXtcclxuXHQgICAgXHRsZXQgdXJsID0gJy9mcmVpZ2h0L3F1b3RlJztcclxuXHRcdFx0cmV0dXJuIHRoaXMuJGh0dHAucG9zdCh1cmwsIGRhdGEpLnRoZW4oKHJlc3BvbnNlKSA9PiB7XHJcblx0XHRcdC8vIHN1Y2Nlc3MgY2FsbGJhY2tcclxuXHRcdFx0Ly9jb25zb2xlLmxvZyhyZXNwb25zZS5ib2R5LnF1b3Rlcyk7XHJcblx0XHRcdGxldCBxdW90ZXMgPSByZXNwb25zZS5ib2R5LnF1b3RlcztcclxuXHJcblx0XHRcdC8vIGZvciBldmVyeSBjb21wYW55L21ldGhvZC9xdW90ZXMgbGlzdFxyXG5cdFx0XHQvLyB0b3RhbCBxdW90ZSBsaXN0IGNvc3QgYW5kIGFzc2lnIHRvXHJcblx0XHRcdC8vIGNvbXBhbnkvbWV0aG9kL3RvdGFsXHJcblx0XHRcdHZhciBzZWxmID0gdGhpc1xyXG5cdFx0XHQgXy5lYWNoKHF1b3RlcywgZnVuY3Rpb24ocSkge1xyXG5cdFx0XHRcdF8uZWFjaChxLCBmdW5jdGlvbihmbWV0aG9kLCBmY29tcGFueSkge1xyXG5cdFx0XHRcdFx0Zm1ldGhvZFsndG90YWwnXSA9IF8uY2hhaW4oZm1ldGhvZC5xdW90ZXMpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0Lm1hcCgnY29zdCcpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0LnN1bSgpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0LnZhbHVlKClcclxuXHJcblx0XHRcdFx0XHR2YXIgcGFja2FnZV9pZHMgPSBfLm1hcChzZWxmLnBhY2thZ2VzLCdpZCcpXHJcblx0XHRcdFx0XHR2YXIgcXVvdGVfcGFja2FnZV9pZHMgPSBcdF8uY2hhaW4oZm1ldGhvZC5xdW90ZXMpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC5tYXAoJ3BhY2thZ2VfaWQnKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQudmFsdWUoKVxyXG5cclxuXHRcdFx0XHRcdC8vIGNvbnNvbGUubG9nKCdwaWRzJywgcGFja2FnZV9pZHMpXHJcblxyXG5cdFx0XHRcdFx0Ly8gY29uc29sZS5sb2coJ3FwaWRzJywgcXVvdGVfcGFja2FnZV9pZHMpXHJcblxyXG5cdFx0XHRcdFx0Zm1ldGhvZFsnbWlzc2VkX3BhY2thZ2VzJ10gPSBfLmRpZmZlcmVuY2UocGFja2FnZV9pZHMscXVvdGVfcGFja2FnZV9pZHMpXHJcblxyXG5cdFx0XHRcdFx0XHJcblx0XHRcdFx0fSlcclxuXHRcdFx0fSlcclxuXHJcblx0XHRcdHRoaXMuY29tcGFuaWVzID0gcmVzcG9uc2UuYm9keS5jb21wYW5pZXM7XHJcblx0XHRcdHRoaXMucXVvdGVzID0gcXVvdGVzO1xyXG5cdFx0XHQvLyBfLmVhY2gocXVvdGVzLCBmdW5jdGlvbihxdW90ZSxjb21wYW55KXtcclxuXHRcdFx0Ly8gXHRjb25zb2xlLmxvZyhxdW90ZSlcclxuXHRcdFx0Ly8gICAgIHRoaXMucXVvdGVzLnB1c2gocXVvdGUpO1xyXG5cdFx0XHQvLyB9LmJpbmQodGhpcykpO1xyXG5cclxuXHRcdFx0fSwgKHJlc3BvbnNlKSA9PiB7XHJcblx0XHRcdC8vIGVycm9yIGNhbGxiYWNrXHJcblx0XHRcdC8vY29uc29sZS5sb2cocmVzcG9uc2UpO1xyXG5cdFx0XHR9KTtcclxuXHQgICAgfSxcclxuICAgICAgICBxdWVyeVNlYXJjaEFzeW5jKHF1ZXJ5U3RyaW5nLCBjYikge1xyXG4gICAgICAgIC8vbGV0IHF1ZXJ5U3RyaW5nTGVuZ3RoID0gdHlwZW9mIHF1ZXJ5U3RyaW5nLmxlbmd0aCAhPT0gJ3VuZGVmbmVkJyA/IHF1ZXJ5U3RyaW5nLmxlbmd0aCA6IDBcclxuICAgICAgICAvL2NvbnNvbGUubG9nKHF1ZXJ5U3RyaW5nLmxlbmd0aCk7XHJcbiAgICAgICAgaWYoIHF1ZXJ5U3RyaW5nLmxlbmd0aCA8IDMgKXtcclxuICAgICAgICAgICAgIGNiKFt7J3ZhbHVlJzonbWluIHRocmVlIGNoYXJhY3RlcnMnfV0pXHJcbiAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgIGxldCB1cmwgPSAnL2ZyZWlnaHQvbG9jYXRpb24/cG9zdGNvZGU9JyArIHF1ZXJ5U3RyaW5nXHJcbiAgICAgICAgICAgIHJldHVybiB0aGlzLiRodHRwLmdldCh1cmwpLnRoZW4oKHJlc3BvbnNlKSA9PiB7XHJcbiAgICAgICAgICAgIC8vIHN1Y2Nlc3MgY2FsbGJhY2tcclxuICAgICAgICAgICAgLy8gY29uc29sZS5sb2cocmVzcG9uc2UuYm9keSk7XHJcbiAgICAgICAgICAgIGxldCBvcHRpb25zID0gcmVzcG9uc2UuYm9keTtcclxuICAgICAgICAgICAgY2Iob3B0aW9ucylcclxuXHJcbiAgICAgICAgICAgIH0sIChyZXNwb25zZSkgPT4ge1xyXG4gICAgICAgICAgICAvLyBlcnJvciBjYWxsYmFja1xyXG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKHJlc3BvbnNlKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgIH0sXHJcbiAgICAgIGhhbmRsZUxvY2F0aW9uU2VsZWN0KGl0ZW0pIHtcclxuICAgICAgICAvLyB2YWx1ZSA9J3Bvc3Rjb2RlLCBzdWJ1cmIsIHN0YXRlJ1xyXG4gICAgICAgIGxldCBsb2MgPSBpdGVtLnZhbHVlLnNwbGl0KCcsJylcclxuICAgICAgICB0aGlzLnBvc3Rjb2RlID0gbG9jWzBdLnRyaW0oKVxyXG4gICAgICAgIHRoaXMuc3VidXJiID0gbG9jWzFdLnRyaW0oKVxyXG4gICAgICAgIHRoaXMuc3RhdGVjb2RlID0gbG9jWzJdLnRyaW0oKVxyXG5cclxuICAgICAgICBpZiAoICAgIHRoaXMucG9zdGNvZGUubGVuZ3RoID4gMiAmJlxyXG4gICAgICAgICAgICAgICAgdGhpcy5zdWJ1cmIubGVuZ3RoID4gMyAmJlxyXG4gICAgICAgICAgICAgICAgdGhpcy5zdGF0ZWNvZGUubGVuZ3RoID4gMSApe1xyXG4gICAgICAgICAgICAvLyBzZXQgbG9jYXRpb25WYWxpZFxyXG4gICAgICAgICAgICB0aGlzLmxvY2F0aW9uVmFsaWQgPSB0cnVlXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBjbGVhciBvdXQgcXVvdGVzIGFzIHRoZXkgY291bGQgYmUgaW52YWxpZFxyXG4gICAgICAgIHRoaXMucXVvdGVzID0gW11cclxuICAgICAgfSxcclxuXHJcbiAgICAgXHJcblx0ICAgIFxyXG4gICAgICBsb29rdXBDbGllbnRBc3luYyhxdWVyeVN0cmluZywgY2IpIHtcclxuICAgICAgXHQvL2xldCBxdWVyeVN0cmluZ0xlbmd0aCA9IHR5cGVvZiBxdWVyeVN0cmluZy5sZW5ndGggIT09ICd1bmRlZm5lZCcgPyBxdWVyeVN0cmluZy5sZW5ndGggOiAwXHJcbiAgICAgIFx0Ly9jb25zb2xlLmxvZyhxdWVyeVN0cmluZy5sZW5ndGgpO1xyXG4gICAgICBcdGlmKCBxdWVyeVN0cmluZy5sZW5ndGggPCAzICl7XHJcbiAgICAgIFx0XHQgY2IoW3sndmFsdWUnOidtaW4gdGhyZWUgY2hhcmFjdGVycyd9XSlcclxuICAgICAgXHRcdCByZXR1cm47XHJcbiAgICAgIFx0fVxyXG4gICAgICAgICBcclxuICAgICAgICAgICAgbGV0IHVybCA9ICcvY2xpZW50bG9va3VwP3E9JyArIHF1ZXJ5U3RyaW5nXHJcblx0XHRcdHJldHVybiB0aGlzLiRodHRwLmdldCh1cmwpLnRoZW4oKHJlc3BvbnNlKSA9PiB7XHJcblx0XHRcdC8vIHN1Y2Nlc3MgY2FsbGJhY2tcclxuXHRcdFx0Y29uc29sZS5sb2cocmVzcG9uc2UuYm9keSk7XHJcblx0XHRcdGxldCByZXN1bHRzID0gcmVzcG9uc2UuYm9keTtcclxuXHJcbiAgICAgICAgICAgIGxldCBvcHRpb25zID0gXy5tYXAocmVzdWx0cywgZnVuY3Rpb24ocikge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHsgdmFsdWU6IHIubmFtZSArICcsICcgKyByLnBvc3Rjb2RlICsgJywgJyArIHIuY2l0eSAgKyAnLCAnICsgci5zdGF0ZSB9XHJcbiAgICAgICAgICAgIH0pXHJcblxyXG4gICAgICAgICAgICBjb25zb2xlLmxvZyhvcHRpb25zKVxyXG5cdFx0XHRjYihvcHRpb25zKVxyXG5cclxuXHRcdFx0fSwgKHJlc3BvbnNlKSA9PiB7XHJcblx0XHRcdC8vIGVycm9yIGNhbGxiYWNrXHJcblx0XHRcdC8vY29uc29sZS5sb2cocmVzcG9uc2UpO1xyXG5cdFx0XHR9KTtcclxuICAgICAgfSxcclxuICAgICAgaGFuZGxlQ2xpZW50U2VsZWN0KGl0ZW0pIHtcclxuICAgICAgICAvLyB2YWx1ZSA9J3Bvc3Rjb2RlLCBzdWJ1cmIsIHN0YXRlJ1xyXG4gICAgICAgIGxldCBsb2MgPSBpdGVtLnZhbHVlLnNwbGl0KCcsJylcclxuICAgICAgICB0aGlzLnBvc3Rjb2RlID0gbG9jWzFdLnRyaW0oKVxyXG4gICAgICAgIHRoaXMuc3VidXJiID0gbG9jWzJdLnRyaW0oKVxyXG4gICAgICAgIHRoaXMuc3RhdGVjb2RlID0gbG9jWzNdLnRyaW0oKVxyXG5cclxuICAgICAgICBpZiAoICAgIHRoaXMucG9zdGNvZGUubGVuZ3RoID4gMiAmJlxyXG4gICAgICAgICAgICAgICAgdGhpcy5zdWJ1cmIubGVuZ3RoID4gMyAmJlxyXG4gICAgICAgICAgICAgICAgdGhpcy5zdGF0ZWNvZGUubGVuZ3RoID4gMSApe1xyXG4gICAgICAgICAgICAvLyBzZXQgbG9jYXRpb25WYWxpZFxyXG4gICAgICAgICAgICB0aGlzLmxvY2F0aW9uVmFsaWQgPSB0cnVlXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBjbGVhciBvdXQgcXVvdGVzIGFzIHRoZXkgY291bGQgYmUgaW52YWxpZFxyXG4gICAgICAgIHRoaXMucXVvdGVzID0gW11cclxuICAgICAgfVxyXG5cclxuICAgIH0sIC8vIGVuZCBvZiBtZXRob2RzXHJcbiAgICBcclxuXHJcbiAgICBcclxufSk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHJlc291cmNlcy9hc3NldHMvanMvZnJlaWdodGNhbGMuanMiXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBK0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7O0FBV0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7OztBQUtBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUFBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7OztBQUlBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7OztBQUtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7OztBQU1BOzs7QUFHQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==");

/***/ }
/******/ ]);