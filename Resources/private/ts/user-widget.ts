/// <reference path="../../../../../../typings/index.d.ts" />

import * as $ from 'jquery';
import * as _ from 'underscore';
import * as Bootstrap from 'bootstrap';
import * as Modal from 'ekyna-modal';
import * as Ui from 'ekyna-ui';
import * as Dispatcher from 'ekyna-dispatcher';

//noinspection JSUnusedLocalSymbols
let bs = Bootstrap;
//noinspection JSUnusedLocalSymbols
let ui = Ui;

interface Config {
    widgetSelector: string
    contentSelector: string
    toggleSelector: string
}

class UserEvent {
    authenticated:boolean;
}

class UserWidget {

    private config:Config;
    private enabled:boolean;
    private authenticated:boolean;
    private contentXHR:JQueryXHR;
    private preventWindowFocusLoadContent:boolean;

    private $widget:JQuery;
    private $content:JQuery;
    private $toggle:JQuery;

    private modalLinkClickHandler:(e:JQueryEventObject) => boolean;
    private contentShowHandler:() => void;
    private xhrErrorHandler:() => void;
    private windowFocusHandler:() => void;

    constructor() {
        this.enabled = false;
        this.preventWindowFocusLoadContent = false;
    }

    initialize(config?:Config):UserWidget {
        this.config = _.defaults(config || {}, {
            widgetSelector: '#user-widget',
            toggleSelector: '> a',
            contentSelector: '> div'
        });

        this.$widget = $(this.config.widgetSelector);
        if (1 != this.$widget.length) {
            throw 'Widget not found ! (' + this.config.widgetSelector + ')';
        }
        this.authenticated = 1 === parseInt(this.$widget.data('status'));

        this.$content = this.$widget.find(this.config.contentSelector);
        if (1 != this.$content.length) {
            throw 'Widget content not found ! (' + this.config.contentSelector + ')';
        }

        this.$toggle = this.$widget.find(this.config.toggleSelector);
        if (1 != this.$toggle.length) {
            throw 'Widget toggle button not found ! (' + this.config.toggleSelector + ')';
        }

        this.modalLinkClickHandler = _.bind(this.onModalLinkClick, this);
        this.contentShowHandler = _.bind(this.onContentShow, this);
        this.xhrErrorHandler = _.bind(this.onXhrError, this);
        this.windowFocusHandler = _.bind(this.onWindowFocus, this);

        return this;
    }

    enable():UserWidget {
        if (this.enabled) {
            return;
        }

        this.enabled = true;

        this.$widget.on('show.bs.dropdown', this.contentShowHandler);

        $(document).on('click', '[data-user-modal]', this.modalLinkClickHandler);
        $(document).on('ajaxError', this.xhrErrorHandler);
        $(window).on('focus', this.windowFocusHandler);

        return this;
    }

    disable():UserWidget {
        if (!this.enabled) {
            return;
        }

        this.enabled = false;

        this.$widget.off('show.bs.dropdown', this.contentShowHandler);

        $(document).off('click', '[data-user-modal]', this.modalLinkClickHandler);
        $(document).off('ajaxError', this.xhrErrorHandler);
        $(window).off('focus', this.windowFocusHandler);

        return this;
    }

    private setAuthenticated(authenticated:boolean, silent:boolean = false) {
        if (this.authenticated !== authenticated) {
            this.authenticated = authenticated;

            if (!silent) {
                let event = new UserEvent();
                event.authenticated = authenticated;
                Dispatcher.trigger('ekyna_user.user_status', event);
            }
        }
    }

    // noinspection JSUnusedLocalSymbols
    onXhrError(event: JQueryEventObject, jqXHR: JQueryXHR):void {
        if (403 === jqXHR.status) {
            this.setAuthenticated(false, true);
            this.openModal(Router.generate('fos_user_security_login'));
        }
    }

    onWindowFocus() {
        if (!this.preventWindowFocusLoadContent) {
            this.preventWindowFocusLoadContent = true;

            setTimeout(() => { this.preventWindowFocusLoadContent = false; }, 10000);

            this.loadContent();
        }
    }

    onContentShow():void {
        if (this.$content.is(':empty')) {
            this.loadContent();
        }
    }

    onModalLinkClick(clickEvent?:JQueryEventObject):boolean {
        clickEvent.preventDefault();

        this.openModal($(clickEvent.target).attr('href'));

        return false;
    }

    openModal(url:string):Ekyna.Modal {
        let modal:Ekyna.Modal = new Modal();

        $(modal).on('ekyna.modal.response', (modalEvent:Ekyna.ModalResponseEvent) => {
            if (modalEvent.contentType == 'xml') {
                let content:string = this.parseResponse(modalEvent.content);
                if (null !== content && this.authenticated) {
                    modalEvent.preventDefault();

                    let dialog:BootstrapDialog = modal.getDialog();
                    dialog.getModalBody().html(content);

                    let buttons:Array<BootstrapDialogButtonOptions> = [];
                    dialog.getButtons().forEach(function(button:BootstrapDialogButtonOptions) {
                        if (button.id === 'close') {
                            buttons.push(button);
                            return false;
                        }
                    });
                    dialog.setButtons(buttons).enableButtons(true);
                }
            } else if (modalEvent.contentType == 'json') {
                modalEvent.preventDefault();

                if (modalEvent.content.success) {
                    this.loadContent();
                    modal.close();
                }
            }
        });

        modal.load({
            url: url,
            method: 'GET'
        });

        return modal;
    }

    loadContent():void {
        this.$content.loadingSpinner('on');

        if (this.contentXHR) {
            this.contentXHR.abort();
        }

        this.contentXHR = $.ajax({
            url: this.$widget.data('url'),
            dataType: 'xml',
            cache: false,
        });

        this.contentXHR.done((xml:XMLDocument) => this.parseResponse(xml));

        this.contentXHR.fail(function() {
            console.log('Failed to load account widget content.')
        });
    }

    parseResponse(xml:XMLDocument):string {
        let widgetNode:Element = xml.querySelector('user-widget');
        if (widgetNode) {
            this.$content
                .loadingSpinner('off')
                .html(widgetNode.textContent);

            this.setAuthenticated(1 === parseInt(widgetNode.getAttribute('status')));

            return widgetNode.textContent;
        }

        return null;
    }
}

export = new UserWidget();
