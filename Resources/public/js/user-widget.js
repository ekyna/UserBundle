define(["require","exports","jquery","underscore","bootstrap","ekyna-modal","ekyna-ui","ekyna-dispatcher"],function(a,b,c,d,e,f,g,h){"use strict";var i=function(){function a(){}return a}(),j=function(){function a(){this.enabled=!1,this.preventWindowFocusLoadContent=!1}return a.prototype.initialize=function(a){if(this.config=d.defaults(a||{},{debug:!1,widgetSelector:"#user-widget",toggleSelector:"> a",contentSelector:"> div"}),this.$widget=c(this.config.widgetSelector),1!=this.$widget.length)throw"Widget not found ! ("+this.config.widgetSelector+")";if(this.authenticated=1===parseInt(this.$widget.data("status")),this.$content=this.$widget.find(this.config.contentSelector),1!=this.$content.length)throw"Widget content not found ! ("+this.config.contentSelector+")";if(this.$toggle=this.$widget.find(this.config.toggleSelector),1!=this.$toggle.length)throw"Widget toggle button not found ! ("+this.config.toggleSelector+")";return this.modalLinkClickHandler=d.bind(this.onModalLinkClick,this),this.contentShowHandler=d.bind(this.onContentShow,this),this.xhrErrorHandler=d.bind(this.onXhrError,this),this.windowFocusHandler=d.bind(this.onWindowFocus,this),this},a.prototype.enable=function(){if(!this.enabled)return this.enabled=!0,this.$widget.on("show.bs.dropdown",this.contentShowHandler),c(document).on("click","[data-user-modal]",this.modalLinkClickHandler),c(document).on("ajaxError",this.xhrErrorHandler),this.config.debug||c(window).on("focus",this.windowFocusHandler),this},a.prototype.disable=function(){if(this.enabled)return this.enabled=!1,this.$widget.off("show.bs.dropdown",this.contentShowHandler),c(document).off("click","[data-user-modal]",this.modalLinkClickHandler),c(document).off("ajaxError",this.xhrErrorHandler),this.config.debug||c(window).off("focus",this.windowFocusHandler),this},a.prototype.setAuthenticated=function(a,b){if(void 0===b&&(b=!1),this.authenticated!==a&&(this.authenticated=a,!b)){var c=new i;c.authenticated=a,h.trigger("ekyna_user.user_status",c)}},a.prototype.onXhrError=function(a,b){403===b.status&&(this.setAuthenticated(!1,!0),this.openModal(Router.generate("fos_user_security_login")))},a.prototype.onWindowFocus=function(){var a=this;this.preventWindowFocusLoadContent||(this.preventWindowFocusLoadContent=!0,setTimeout(function(){a.preventWindowFocusLoadContent=!1},1e4),this.loadContent())},a.prototype.onContentShow=function(){this.$content.is(":empty")&&this.loadContent()},a.prototype.onModalLinkClick=function(a){return a.preventDefault(),this.openModal(c(a.target).attr("href")),!1},a.prototype.openModal=function(a){var b=this,d=new f;return c(d).on("ekyna.modal.response",function(a){if("xml"==a.contentType){var c=b.parseResponse(a.content);if(null!==c&&b.authenticated){a.preventDefault();var e=d.getDialog();e.getModalBody().html(c);var f=[];e.getButtons().forEach(function(a){if("close"===a.id)return f.push(a),!1}),e.setButtons(f).enableButtons(!0)}}else"json"==a.contentType&&(a.preventDefault(),a.content.success&&(b.loadContent(),a.modal.close()))}),d.load({url:a,method:"GET"}),d},a.prototype.loadContent=function(){var a=this;this.$content.loadingSpinner("on"),this.contentXHR&&this.contentXHR.abort(),this.contentXHR=c.ajax({url:this.$widget.data("url"),dataType:"xml",cache:!1}),this.contentXHR.done(function(b){return a.parseResponse(b)}),this.contentXHR.fail(function(){console.log("Failed to load account widget content.")})},a.prototype.parseResponse=function(a){var b=a.querySelector("user-widget");return b?(this.$content.loadingSpinner("off").html(b.textContent),this.setAuthenticated(1===parseInt(b.getAttribute("status"))),b.textContent):null},a}();return new j});