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

eval("\r\n\r\nvar app =new Vue({\r\n    el: '#table',\r\n    data: {\r\n        columns: [\r\n            { label: 'ID', field: 'id', align: 'center', filterable: false },\r\n            { label: 'Username', field: 'user.username' },\r\n            { label: 'First Name', field: 'user.first_name' },\r\n            { label: 'Last Name', field: 'user.last_name' },\r\n            { label: 'Email', field: 'user.email', align: 'right', sortable: false }\r\n        ],\r\n        rows: window.rows\r\n    }\r\n});//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2NhbGxfcGxhbm5lci5qcz83NDkwIl0sInNvdXJjZXNDb250ZW50IjpbIlxyXG5cclxuY29uc3QgYXBwID1uZXcgVnVlKHtcclxuICAgIGVsOiAnI3RhYmxlJyxcclxuICAgIGRhdGE6IHtcclxuICAgICAgICBjb2x1bW5zOiBbXHJcbiAgICAgICAgICAgIHsgbGFiZWw6ICdJRCcsIGZpZWxkOiAnaWQnLCBhbGlnbjogJ2NlbnRlcicsIGZpbHRlcmFibGU6IGZhbHNlIH0sXHJcbiAgICAgICAgICAgIHsgbGFiZWw6ICdVc2VybmFtZScsIGZpZWxkOiAndXNlci51c2VybmFtZScgfSxcclxuICAgICAgICAgICAgeyBsYWJlbDogJ0ZpcnN0IE5hbWUnLCBmaWVsZDogJ3VzZXIuZmlyc3RfbmFtZScgfSxcclxuICAgICAgICAgICAgeyBsYWJlbDogJ0xhc3QgTmFtZScsIGZpZWxkOiAndXNlci5sYXN0X25hbWUnIH0sXHJcbiAgICAgICAgICAgIHsgbGFiZWw6ICdFbWFpbCcsIGZpZWxkOiAndXNlci5lbWFpbCcsIGFsaWduOiAncmlnaHQnLCBzb3J0YWJsZTogZmFsc2UgfVxyXG4gICAgICAgIF0sXHJcbiAgICAgICAgcm93czogd2luZG93LnJvd3NcclxuICAgIH1cclxufSk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHJlc291cmNlcy9hc3NldHMvanMvY2FsbF9wbGFubmVyLmpzIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VSb290IjoiIn0=");

/***/ }
/******/ ]);