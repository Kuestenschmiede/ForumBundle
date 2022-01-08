/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

!(function (jQuery) {

  jQuery.fn.serializeObject = function () {
    "use strict";

    var result = {};
    var extend = function (i, element) {
      var node = result[element.name];
      if ('undefined' !== typeof node && node !== null) {
        if (jQuery.isArray(node)) {
          node.push(element.value);
        } else {
          result[element.name] = [node, element.value];
        }
      } else {
        result[element.name] = element.value;
      }
    };
    jQuery.each(this.serializeArray(), extend);
    return result;
  };

  /**
   *
   * @returns {{sendMessage: _sendMessage, deleteMessage: _deleteMessage, openModal: _openModal, parseHTMLCode: _parseHTMLCode}}
   * @constructor
   */
  var C4gPn = function () {

    /**
     *
     * @param type
     * @param data
     * @private
     */
    var _openModal = function (type, data, title) {
      data = data || {};
      title = title || "";
      var minWidth = 480;
      var minHeight = 360;

      var aButtons = [
        {
          text: C4GLANG.close,
          icons: {
            primary: "ui-icon-close"
          },
          click: function () {
            jQuery(this).dialog("close");
            jQuery('#modal-' + type).remove();
          }

          // Uncommenting the following line would hide the text,
          // resulting in the label being used as a tooltip
          //showText: false
        }
      ];

      if (type == "compose") {

        minWidth = 480;
        minHeight = 360;
        aButtons.unshift(
          {
            text: C4GLANG.send,
            icons: {
              primary: "ui-icon-arrowreturnthick-1-w"
            },
            click: function () {
              jQuery('#frmCompose').submit();
            }
          }
        );
      }
      if (type == "view") {

        minWidth = 480;
        minHeight = 360;

        aButtons.unshift({
          text: C4GLANG.delete,
          icons: {
            primary: "ui-icon-trash"
          },
          click: function () {
            _deleteMessage(data.id);
            jQuery(this).dialog("close");
          }
        });
        aButtons.unshift({
          text: C4GLANG.reply,
          icons: {
            primary: "ui-icon-arrowreturnthick-1-w"
          },
          click: function () {
            var sSubject = 'RE: ' + jQuery('.subject').text();
            _sendMessageTo(data.sid, sSubject, sSubject, this);
            jQuery(this).dialog("close");
          }
        });
      }

      jQuery.ajax({
        method: "GET",
        //url: "system/modules/con4gis_forum/api/index.php/modal/" + type,
        url: pnApiBaseUrl + "/modal/" + type,
        data: {data: data},
        success: function (response) {
          jQuery('#modal-' + type).remove();
          jQuery("body").append(response.template);
          jQuery('#modal-' + type).dialog({
            title: title,
            minWidth: minWidth,
            minHeight: minHeight,
            close: function (event, ui) {
              jQuery('#modal-' + type).remove();
            },
            buttons: aButtons
          });
          var frmCompose = document.getElementById('frmCompose');
          if ((typeof(frmCompose) !== 'undefined') && (frmCompose !== null)){
              frmCompose.dataset.target = data.target;
          }
        }
      });
    };


    /**
     *
     * @param id
     * @private
     */
    var _deleteMessage = function (id) {
      var bConfirm = confirm(C4GLANG.delete_confirm);
      if (bConfirm == true) {
        jQuery.ajax({
          method: "DELETE",
          //url: "system/modules/con4gis_forum/api/index.php/delete/" + id,
          url: pnApiBaseUrl + "/delete/" + id,
          success: function (response) {
            if (response.success == true) {
              jQuery('#message-' + id).remove();
            }
          }
        });
      }
    };


    /**
     *
     * @param frm
     * @private
     */
    var _sendMessage = function (frm) {
      var data = jQuery(frm).serializeObject();
      data.target = frm.dataset.target;
      jQuery.ajax({
        method: "POST",
        //url: "system/modules/con4gis_forum/api/index.php/send/",
        url: pnApiBaseUrl + "/send/",
        data: data,
        success: function (response) {
          if (response.success !== true) {
            alert(C4GLANG.send_error);
          } else {
            jQuery('#modal-compose').dialog('close');
          }
        }
      });
    };
    /**
     *
     * @param frm
     * @private
     */
    var _sendMessageTo = function (iUserId, subject, title, opt_this) {
      if (typeof(event) !== 'undefined') {
          event.preventDefault();
      }
      subject = subject || "";
      title = title || "";
      _openModal('compose', {recipient_id: iUserId, subject: subject, target:opt_this.getAttribute('data-target')},title);
    };


    /**
     *
     * @param selector
     * @private
     */
    var _parseHTMLCode = function (selector) {
      var cont = document.createElement('div');
      cont.innerHTML=jQuery(selector).html();
      jQuery(selector).html( jQuery(cont).text() );
    };

    var _markAsRead = function (id) {
      var data = {
        status: 1,
        id: id
      };
      jQuery.ajax({
        method: "POST",
        //url: "system/modules/con4gis_forum/api/index.php/mark",
        url: pnApiBaseUrl + "/mark",
        data: data,
        success: function (response) {
          if (response.success !== true) {
            alert(response.message);
          } else {
            jQuery('#message-' + id + '.unread').removeClass('unread').addClass('read');
          }
        }
      });
    };


    return {
      sendMessage: _sendMessage,
      sendMessageTo: _sendMessageTo,
      deleteMessage: _deleteMessage,
      openModal: _openModal,
      parseHTMLCode: _parseHTMLCode,
      markAsRead: _markAsRead
    };
  };

  // init
  window.C4gPn = new C4gPn();

})(jQuery);