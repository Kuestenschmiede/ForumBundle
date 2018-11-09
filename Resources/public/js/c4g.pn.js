!(function ($) {

  $.fn.serializeObject = function () {
    "use strict";

    var result = {};
    var extend = function (i, element) {
      var node = result[element.name];
      if ('undefined' !== typeof node && node !== null) {
        if ($.isArray(node)) {
          node.push(element.value);
        } else {
          result[element.name] = [node, element.value];
        }
      } else {
        result[element.name] = element.value;
      }
    };
    $.each(this.serializeArray(), extend);
    return result;
  };

  /**
   *
   * @returns {{sendMessage: _sendMessage, deleteMessage: _deleteMessage, openModal: _openModal, parseHTMLCode: _parseHTMLCode, initCKEditor: _initCKEditor}}
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
            $(this).dialog("close");
            $('#modal-' + type).remove();
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
              $('#frmCompose').submit();
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
            $(this).dialog("close");
          }
        });
        aButtons.unshift({
          text: C4GLANG.reply,
          icons: {
            primary: "ui-icon-arrowreturnthick-1-w"
          },
          click: function () {
            var sSubject = 'RE: ' + $('.subject').text();
            _sendMessageTo(data.sid, sSubject, sSubject, this);
            $(this).dialog("close");
          }
        });
      }

      $.ajax({
        method: "GET",
        //url: "system/modules/con4gis_forum/api/index.php/modal/" + type,
        url: pnApiBaseUrl + "/modal/" + type,
        data: {data: data},
        success: function (response) {
          $('#modal-' + type).remove();
          $("body").append(response.template);
          $('#modal-' + type).dialog({
            title: title,
            minWidth: minWidth,
            minHeight: minHeight,
            close: function (event, ui) {
              $('#modal-' + type).remove();
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
        $.ajax({
          method: "DELETE",
          //url: "system/modules/con4gis_forum/api/index.php/delete/" + id,
          url: pnApiBaseUrl + "/delete/" + id,
          success: function (response) {
            if (response.success == true) {
              $('#message-' + id).remove();
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
      var data = $(frm).serializeObject();
      data.target = frm.dataset.target;
      $.ajax({
        method: "POST",
        //url: "system/modules/con4gis_forum/api/index.php/send/",
        url: pnApiBaseUrl + "/send/",
        data: data,
        success: function (response) {
          if (response.success !== true) {
            alert(C4GLANG.send_error);
          } else {
            $('#modal-compose').dialog('close');
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
      cont.innerHTML=$(selector).html();
      $(selector).html( $(cont).text() );
    };


    /**
     *
     * @private
     */
    var _initCKEditor = function () {


      var ckEditorItems = ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'Blockquote', '-', 'RemoveFormat', 'NumberedList', 'BulletedList', 'Link', 'Unlink', 'Anchor', 'Image', 'TextColor', 'BGColor'];
      var editor = CKEDITOR.replace('ckeditor', {
        toolbar: [{
          name: 'all',
          items: ckEditorItems
        }],
        // removePlugins:'',
        extraPlugins: 'justify,fileUpload,bbcode,panelbutton,floatpanel,colorbutton,blockquote,youtube',
        language: sCurrentLang,
        defaultLanguage: "en",
        height:'360',
        disableObjectResizing: true,
        filebrowserImageUploadUrl: "bundles/con4giscore/vendor/imgUpload.php",
        filebrowserUploadUrl: 'bundles/con4giscore/vendor/C4GFileUpload.php',
        // codeSnippet_languages: {
        // }
      });

      CKEDITOR.on('instanceReady', function () {
        jQuery.each(CKEDITOR.instances, function (instance) {
          CKEDITOR.instances[instance].on("change", function (e) {
            for (instance in CKEDITOR.instances)
              CKEDITOR.instances[instance].updateElement();
          });
        });
      });

      editor.focus();
    };


    var _markAsRead = function (id) {
      var data = {
        status: 1,
        id: id
      };
      $.ajax({
        method: "POST",
        //url: "system/modules/con4gis_forum/api/index.php/mark",
        url: pnApiBaseUrl + "/mark",
        data: data,
        success: function (response) {
          if (response.success !== true) {
            alert(response.message);
          } else {
            $('#message-' + id + '.unread').removeClass('unread').addClass('read');
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
      initCKEditor: _initCKEditor,
      markAsRead: _markAsRead
    };
  };

  // init
  window.C4gPn = new C4gPn();

})(jQuery);