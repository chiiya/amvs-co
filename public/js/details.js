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

eval("var   likes = document.getElementsByClassName('like'),\r\n        unlikes = document.getElementsByClassName('unlike'),\r\n        unfilled = document.getElementsByClassName('amv__like--unfilled'),\r\n        filled = document.getElementsByClassName('amv__like--filled')\r\n        data = {\r\n            amv_id : document.head.querySelector(\"[name=amv]\").id\r\n        };\r\n\r\nvar like = function() {\r\n    Array.from(unfilled).forEach(function(element) {\r\n      element.style.display=\"none\";\r\n    });\r\n    Array.from(filled).forEach(function(element) {\r\n      element.style.display=\"block\";\r\n    });\r\n    Vue.http.post((\"/api/amvs/\" + (data.amv_id) + \"/likes\"), data, {\r\n        headers: {\r\n            'Content-Type': 'application/json'\r\n        }\r\n        });\r\n}\r\n\r\nvar unlike = function() {\r\n    Array.from(unfilled).forEach(function(element) {\r\n      element.style.display=\"block\";\r\n    });\r\n    Array.from(filled).forEach(function(element) {\r\n      element.style.display=\"none\";\r\n    });\r\n    Vue.http.delete((\"/api/amvs/\" + (data.amv_id) + \"/likes\"));\r\n}\r\n\r\nArray.from(likes).forEach(function(element) {\r\n      element.addEventListener('click', like);\r\n});\r\n\r\nArray.from(unlikes).forEach(function(element) {\r\n      element.addEventListener('click', unlike);\r\n});\r\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2RldGFpbHMuanM/YTlhZCJdLCJzb3VyY2VzQ29udGVudCI6WyJjb25zdCAgIGxpa2VzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgnbGlrZScpLFxyXG4gICAgICAgIHVubGlrZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd1bmxpa2UnKSxcclxuICAgICAgICB1bmZpbGxlZCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2Ftdl9fbGlrZS0tdW5maWxsZWQnKSxcclxuICAgICAgICBmaWxsZWQgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdhbXZfX2xpa2UtLWZpbGxlZCcpXHJcbiAgICAgICAgZGF0YSA9IHtcclxuICAgICAgICAgICAgYW12X2lkIDogZG9jdW1lbnQuaGVhZC5xdWVyeVNlbGVjdG9yKFwiW25hbWU9YW12XVwiKS5pZFxyXG4gICAgICAgIH07XHJcblxyXG52YXIgbGlrZSA9IGZ1bmN0aW9uKCkge1xyXG4gICAgQXJyYXkuZnJvbSh1bmZpbGxlZCkuZm9yRWFjaChmdW5jdGlvbihlbGVtZW50KSB7XHJcbiAgICAgIGVsZW1lbnQuc3R5bGUuZGlzcGxheT1cIm5vbmVcIjtcclxuICAgIH0pO1xyXG4gICAgQXJyYXkuZnJvbShmaWxsZWQpLmZvckVhY2goZnVuY3Rpb24oZWxlbWVudCkge1xyXG4gICAgICBlbGVtZW50LnN0eWxlLmRpc3BsYXk9XCJibG9ja1wiO1xyXG4gICAgfSk7XHJcbiAgICBWdWUuaHR0cC5wb3N0KGAvYXBpL2FtdnMvJHtkYXRhLmFtdl9pZH0vbGlrZXNgLCBkYXRhLCB7XHJcbiAgICAgICAgaGVhZGVyczoge1xyXG4gICAgICAgICAgICAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL2pzb24nXHJcbiAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG59XHJcblxyXG52YXIgdW5saWtlID0gZnVuY3Rpb24oKSB7XHJcbiAgICBBcnJheS5mcm9tKHVuZmlsbGVkKS5mb3JFYWNoKGZ1bmN0aW9uKGVsZW1lbnQpIHtcclxuICAgICAgZWxlbWVudC5zdHlsZS5kaXNwbGF5PVwiYmxvY2tcIjtcclxuICAgIH0pO1xyXG4gICAgQXJyYXkuZnJvbShmaWxsZWQpLmZvckVhY2goZnVuY3Rpb24oZWxlbWVudCkge1xyXG4gICAgICBlbGVtZW50LnN0eWxlLmRpc3BsYXk9XCJub25lXCI7XHJcbiAgICB9KTtcclxuICAgIFZ1ZS5odHRwLmRlbGV0ZShgL2FwaS9hbXZzLyR7ZGF0YS5hbXZfaWR9L2xpa2VzYCk7XHJcbn1cclxuXHJcbkFycmF5LmZyb20obGlrZXMpLmZvckVhY2goZnVuY3Rpb24oZWxlbWVudCkge1xyXG4gICAgICBlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgbGlrZSk7XHJcbn0pO1xyXG5cclxuQXJyYXkuZnJvbSh1bmxpa2VzKS5mb3JFYWNoKGZ1bmN0aW9uKGVsZW1lbnQpIHtcclxuICAgICAgZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHVubGlrZSk7XHJcbn0pO1xyXG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gcmVzb3VyY2VzL2Fzc2V0cy9qcy9kZXRhaWxzLmpzIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9");

/***/ }
/******/ ]);