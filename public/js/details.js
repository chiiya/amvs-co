/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.l = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// identity function for calling harmory imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };

/******/ 	// define getter function for harmory exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		Object.defineProperty(exports, name, {
/******/ 			configurable: false,
/******/ 			enumerable: true,
/******/ 			get: getter
/******/ 		});
/******/ 	};

/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};

/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

eval("var vm = new Vue({\r\n    el: '#app',\r\n\r\n    data: {\r\n        error: '',\r\n        like: {}\r\n    },\r\n\r\n    methods: {\r\n\r\n        like: function like() {\n            var this$1 = this;\n\r\n            document.querySelector(\".amv__like--unfilled\").style.display=\"none\";\r\n            document.querySelector(\".amv__like--filled\").style.display=\"block\";\r\n            var data = {\r\n                amv_id: document.head.querySelector(\"[name=amv]\").id\r\n            };\r\n            this.$http.post('/api/likes', data, {\r\n                headers: {\r\n                    'Content-Type': 'application/json'\r\n                }\r\n            }).then(function (response) {\r\n                this$1.like = response.body;\r\n            });\r\n        },\r\n\r\n        unlike: function unlike() {\r\n            var filledDiv = document.querySelector(\".amv__like--filled\");\r\n            filledDiv.style.display=\"none\";\r\n            var likeId = this.like.id || filledDiv.getAttribute('likeid');\r\n            document.querySelector(\".amv__like--unfilled\").style.display=\"block\";\r\n\r\n            this.$http.delete((\"/api/likes/\" + likeId));\r\n        }\r\n\r\n    }\r\n})//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2RldGFpbHMuanM/YTlhZCIsIndlYnBhY2s6Ly8vP2Q0MWQiXSwic291cmNlc0NvbnRlbnQiOlsiY29uc3Qgdm0gPSBuZXcgVnVlKHtcclxuICAgIGVsOiAnI2FwcCcsXHJcblxyXG4gICAgZGF0YToge1xyXG4gICAgICAgIGVycm9yOiAnJyxcclxuICAgICAgICBsaWtlOiB7fVxyXG4gICAgfSxcclxuXHJcbiAgICBtZXRob2RzOiB7XHJcblxyXG4gICAgICAgIGxpa2UoKSB7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIuYW12X19saWtlLS11bmZpbGxlZFwiKS5zdHlsZS5kaXNwbGF5PVwibm9uZVwiO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLmFtdl9fbGlrZS0tZmlsbGVkXCIpLnN0eWxlLmRpc3BsYXk9XCJibG9ja1wiO1xyXG4gICAgICAgICAgICBjb25zdCBkYXRhID0ge1xyXG4gICAgICAgICAgICAgICAgYW12X2lkOiBkb2N1bWVudC5oZWFkLnF1ZXJ5U2VsZWN0b3IoXCJbbmFtZT1hbXZdXCIpLmlkXHJcbiAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgIHRoaXMuJGh0dHAucG9zdCgnL2FwaS9saWtlcycsIGRhdGEsIHtcclxuICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcclxuICAgICAgICAgICAgICAgICAgICAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL2pzb24nXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pLnRoZW4oKHJlc3BvbnNlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0aGlzLmxpa2UgPSByZXNwb25zZS5ib2R5O1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICB1bmxpa2UoKSB7XHJcbiAgICAgICAgICAgIGNvbnN0IGZpbGxlZERpdiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIuYW12X19saWtlLS1maWxsZWRcIik7XHJcbiAgICAgICAgICAgIGZpbGxlZERpdi5zdHlsZS5kaXNwbGF5PVwibm9uZVwiO1xyXG4gICAgICAgICAgICBjb25zdCBsaWtlSWQgPSB0aGlzLmxpa2UuaWQgfHwgZmlsbGVkRGl2LmdldEF0dHJpYnV0ZSgnbGlrZWlkJyk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIuYW12X19saWtlLS11bmZpbGxlZFwiKS5zdHlsZS5kaXNwbGF5PVwiYmxvY2tcIjtcclxuXHJcbiAgICAgICAgICAgIHRoaXMuJGh0dHAuZGVsZXRlKGAvYXBpL2xpa2VzLyR7bGlrZUlkfWApO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICB9XHJcbn0pXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHJlc291cmNlcy9hc3NldHMvanMvZGV0YWlscy5qcyIsInVuZGVmaW5lZFxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAiXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBRENBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9");

/***/ }
/******/ ]);