/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/blockEditor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

module.exports = window["wp"]["hooks"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_blockEditor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/blockEditor */ "@wordpress/blockEditor");
/* harmony import */ var _wordpress_blockEditor__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blockEditor__WEBPACK_IMPORTED_MODULE_4__);
/**
 * This code adds AI content generation feature in WordPress.
 * @version 1.0.0
 */

// use strict mode for improved security and error handling


// import necessary functions and components from WordPress libraries







// create a higher order component that adds the AI content generation feature to the BlockEdit component 
const wpaikitControls = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__.createHigherOrderComponent)(BlockEdit => {
  // return a function that takes in props and returns the wrapped BlockEdit component
  return props => {
    // destructure necessary props and state variables
    const {
      attributes,
      setAttributes,
      isSelected
    } = props;
    const [isRunning, setIsRunning] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
    const [aiElementType, setAiElementType] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)("paragraph");
    const [aiBlockAction, setAiBlockAction] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)("create");
    const [aiTask, setAiTask] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(Object.keys(aicontent.prompts)[0]);

    // define an array of allowed block types
    const allowedTags = ["core/quote", "core/heading", "core/paragraph", "core/preformatted", "core/pullquote", "core/verse", "core/list"];

    /**
     * A function that prepares content for insertion or update based on selected block and settings.
     * @param {*} content - The generated AI content.
     * @param {string} aiElementType - The AI element type to insert.
     * @param {string} aiBlockAction - The AI block action to perform, create or update.
     * return The created or updated block.
     */
    const prepareContent = function (content) {
      let aiElementType = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "paragraph";
      let aiBlockAction = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "create";
      // get the client ID of the selected block
      const selectedBlockClientIds = wp.data.select("core/block-editor").getSelectedBlockClientId();
      // get the index of the selected block
      const indexToInsertAt = wp.data.select("core/block-editor").getBlockIndex(selectedBlockClientIds) + 1;
      // determine the block type based on selected element type
      let blockType;
      switch (aiElementType) {
        case "heading":
          blockType = "core/heading";
          break;
        default:
          blockType = "core/paragraph";
      }
      let blockToInsert;
      // if updating an existing block, update the attributes
      if (aiBlockAction === "update") {
        const currentBlock = wp.data.select("core/block-editor").getSelectedBlock();
        if (currentBlock) {
          wp.data.dispatch("core/block-editor").updateBlockAttributes(selectedBlockClientIds, {
            content: content
          });
        }
      } else {
        // if creating a new block, create a new block object and insert it at the selected index

        blockToInsert = wp.blocks.createBlock(blockType, {
          content: content
        });
        wp.data.dispatch("core/block-editor").insertBlock(blockToInsert, indexToInsertAt);
      }
    };
    const handleClick = event => {
      setIsRunning(true);
      const nonce = aicontent.nonce;
      const rest_nonce = aicontent.rest_nonce;
      let text = "";
      if ("content" in attributes) {
        text = attributes.content;
      } else if ("citation" in attributes) {
        text = attributes.citation;
      } else if ("value" in attributes) {
        text = attributes.value;
      } else if ("values" in attributes) {
        text = attributes.values;
      } else if ("text" in attributes) {
        text = attributes.text;
      }
      const response = fetch(aicontent.siteUrl + "/wp-json/aicontent/v1/text", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": rest_nonce
        },
        body: JSON.stringify({
          text: text,
          type: aiTask
        })
      }).then(function (response) {
        // The response is a Response instance.
        // You parse the data into a useable format using `.json()`
        return response.json();
      }).then(function (data) {
        // `data` is the parsed version of the JSON returned from the above endpoint.
        if (typeof data.message != undefined && typeof data.message != undefined) {
          var newContent = data.message;
          prepareContent(newContent, aiElementType, aiBlockAction);
        }
        setIsRunning(false);
      });
    };

    // create an array of options for the select control based on the prompts defined in aicontent
    const promptOptions = Object.entries(aicontent.prompts).map(_ref => {
      let [key, value] = _ref;
      return {
        label: value,
        value: key
      };
    });
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, props), isSelected && allowedTags.includes(props.name) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_blockEditor__WEBPACK_IMPORTED_MODULE_4__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
      title: wp.i18n.__("Text Prompter", "wp-ai-kit")
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
      label: wp.i18n.__("Choose Prompt", "wp-ai-kit"),
      value: aiTask,
      options: promptOptions,
      onChange: newAiTask => setAiTask(newAiTask)
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
      label: wp.i18n.__("Choose Element Type", "wp-ai-kit"),
      value: aiElementType,
      options: [{
        label: "Paragraph",
        value: "paragraph"
      }, {
        label: "Heading",
        value: "heading"
      }],
      onChange: newAiElementType => setAiElementType(newAiElementType)
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.RadioControl, {
      label: wp.i18n.__("Choose Block Action", "wp-ai-kit"),
      selected: aiBlockAction,
      options: [{
        label: "Update Existing Block",
        value: "update"
      }, {
        label: "Create New Block",
        value: "create"
      }],
      onChange: newAiBlockAction => setAiBlockAction(newAiBlockAction)
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      isBusy: isRunning,
      disabled: isRunning,
      variant: "primary",
      onClick: handleClick
    }, wp.i18n.__("Submit", "wp-ai-kit")))));
  };
}, "wpaikitControls");

// add the AI content generation feature in WordPress
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__.addFilter)("editor.BlockEdit", "wpaikit/wp-aikit-controls", wpaikitControls);
})();

/******/ })()
;
//# sourceMappingURL=index.js.map