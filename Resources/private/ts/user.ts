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

class User {
    private authenticated:boolean;

    private modalLinkClickHandler:(e:JQueryEventObject) => boolean;
    private xhrErrorHandler:() => void;

    constructor() {
        this.modalLinkClickHandler = _.bind(this.onModalLinkClick, this);
        this.xhrErrorHandler = _.bind(this.onXhrError, this);

        $(document).on('click', '[data-user-modal]', this.modalLinkClickHandler);
        $(document).on('ajaxError', this.xhrErrorHandler);
    }

    setAuthenticated(authenticated:boolean, silent:boolean = false):void {
        if (this.authenticated !== authenticated) {
            this.authenticated = authenticated;

            if (!silent) {
                Dispatcher.trigger('ekyna_user.authentication', {
                    authenticated: this.authenticated
                });
            }
        }
    }

    // noinspection JSUnusedLocalSymbols
    onXhrError(event: JQueryEventObject, jqXHR: JQueryXHR):void {
        if (403 === jqXHR.status) {
            this.setAuthenticated(false);
            this.openModal(Router.generate('fos_user_security_login'));
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
                    this.setAuthenticated(true);
                    modalEvent.modal.close();
                }
            }
        });

        modal.load({
            url: url,
            method: 'GET'
        });

        return modal;
    }

    parseResponse(xml:XMLDocument):string {
        let widgetNode:Element = xml.querySelector('user-widget');
        if (widgetNode) {
            this.setAuthenticated(1 === parseInt(widgetNode.getAttribute('status')));

            return widgetNode.textContent;
        }

        return null;
    }
}

export = new User();