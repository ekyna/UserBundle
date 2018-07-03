define(["require","exports","jquery","underscore","bootstrap","ekyna-modal","ekyna-ui","ekyna-dispatcher"],function(a,b,c,d,e,f,g,h){"use strict";function i(){return new j}Object.defineProperty(b,"__esModule",{value:!0});var j=function(){function a(){this.modalLinkClickHandler=d.bind(this.onModalLinkClick,this),this.xhrErrorHandler=d.bind(this.onXhrError,this),c(document).on("click","[data-user-modal]",this.modalLinkClickHandler),c(document).on("ajaxError",this.xhrErrorHandler)}return a.prototype.setAuthenticated=function(a,b){void 0===b&&(b=!1),this.authenticated!==a&&(this.authenticated=a,b||h.trigger("ekyna_user.authentication",{authenticated:this.authenticated}))},a.prototype.onXhrError=function(a,b){403===b.status&&(this.setAuthenticated(!1),this.openModal(Router.generate("fos_user_security_login")))},a.prototype.onModalLinkClick=function(a){return a.preventDefault(),this.openModal(c(a.target).attr("href")),!1},a.prototype.openModal=function(a){var b=this,d=new f;return c(d).on("ekyna.modal.response",function(a){if("xml"==a.contentType){var c=b.parseResponse(a.content);if(null!==c&&b.authenticated){a.preventDefault();var e=d.getDialog();e.getModalBody().html(c);var f=[];e.getButtons().forEach(function(a){if("close"===a.id)return f.push(a),!1}),e.setButtons(f).enableButtons(!0)}}else"json"==a.contentType&&(a.preventDefault(),a.content.success&&(b.setAuthenticated(!0),a.modal.close()))}),d.load({url:a,method:"GET"}),d},a.prototype.parseResponse=function(a){var b=a.querySelector("user-widget");return b?(this.setAuthenticated(1===parseInt(b.getAttribute("status"))),b.textContent):null},a}();b.init=i});