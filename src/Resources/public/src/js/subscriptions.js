/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

var forumSubsSubmitChange = document.getElementsByClassName('forum_submit_change_button');
var index = 0;

while (index < forumSubsSubmitChange.length) {
    let currentButton = forumSubsSubmitChange.item(index);
    currentButton.addEventListener('click', function(){
        let url = currentButton.parentNode.action;
        let checkboxes = currentButton.parentNode.getElementsByTagName('input');
        let data = {};
        data.target = currentButton.dataset.target;
        data.deletesub = 0;
        let i = 0;
        while (i < checkboxes.length) {
            data[checkboxes.item(i).value] = checkboxes.item(i).checked ? 1 : 0;
            i += 1;
        }
        jQuery.ajax(url,
            {
                method: "post",
                data: data,
                success: function(response, status, jqXHR) {
                    showInfoDialog(response.message, response.title, 'Ok');
                }
            }
        );
    });
    index += 1;
}

var forumSubsSubmitDelete = document.getElementsByClassName('forum_submit_delete_button');
index = 0;

while (index < forumSubsSubmitDelete.length) {
    let currentButton = forumSubsSubmitDelete.item(index);
    currentButton.addEventListener('click', function(){
        let url = currentButton.parentNode.action;
        jQuery.ajax(url,
            {
                method: "post",
                data: {
                    target: currentButton.dataset.target,
                    deletesub: 1
                },
                success: function(response, status, jqXHR) {
                    showInfoDialog(response.message, response.title, 'Ok');
                    let formElementDiv = currentButton.parentNode.parentNode;
                    let forumSubsDiv = currentButton.parentNode.parentNode.parentNode;
                    forumSubsDiv.removeChild(formElementDiv);
                    if (forumSubsDiv.getElementsByClassName('sub').length === 0) {
                        forumSubsDiv.parentNode.removeChild(forumSubsDiv);
                        if (document.getElementById('thread_subs') === null) {
                            var template = document.getElementById('no_subs');
                            var html = template.innerHTML;
                            var div = document.createElement('div');
                            div.innerHTML = html;
                            template.parentNode.appendChild(div);
                        }
                    }
                }
            }
        )
    });
    index += 1;
}

var threadSubsSubmitChange = document.getElementsByClassName('thread_submit_change_button');
index = 0;

while (index < threadSubsSubmitChange.length) {
    let currentButton = threadSubsSubmitChange.item(index);
    currentButton.addEventListener('click', function(){
        let url = currentButton.parentNode.action;
        let checkboxes = currentButton.parentNode.getElementsByTagName('input');
        let data = {};
        data.target = currentButton.dataset.target;
        data.deletesub = 0;
        let i = 0;
        while (i < checkboxes.length) {
            data[checkboxes.item(i).value] = checkboxes.item(i).checked ? 1 : 0;
            i += 1;
        }
        jQuery.ajax(url,
            {
                method: "post",
                data: data,
                success: function(response, status, jqXHR) {
                    showInfoDialog(response.message, response.title, 'Ok');
                }
            }
        )
    });
    index += 1;
}

var threadSubsSubmitDelete = document.getElementsByClassName('thread_submit_delete_button');
index = 0;

while (index < threadSubsSubmitDelete.length) {
    let currentButton = threadSubsSubmitDelete.item(index);
    currentButton.addEventListener('click', function(){
        let url = currentButton.parentNode.action;
        jQuery.ajax(url,
            {
                method: "post",
                data: {
                    target: currentButton.dataset.target,
                    deletesub: 1
                },
                success: function(response, status, jqXHR) {
                    showInfoDialog(response.message, response.title, 'Ok');
                    let formElementDiv = currentButton.parentNode.parentNode;
                    let threadSubsDiv = currentButton.parentNode.parentNode.parentNode;
                    threadSubsDiv.removeChild(formElementDiv);
                    if (threadSubsDiv.getElementsByClassName('sub').length === 0) {
                        threadSubsDiv.parentNode.removeChild(threadSubsDiv);
                        if (document.getElementById('forum_subs') === null) {
                            var template = document.getElementById('no_subs');
                            var html = template.innerHTML;
                            var div = document.createElement('div');
                            div.innerHTML = html;
                            template.parentNode.appendChild(div);
                        }
                    }
                }
            }
        )
    });
    index += 1;
}

function showInfoDialog(message,title,okLabel){
    jQuery('<div></div>').appendTo('body')
        .html('<div>'+message+'</div>')
        .dialog({
            modal: true, title: title, zIndex: 10000, autoOpen: true,
            width: 'auto', resizable: false,
            buttons: [
                {
                    text: okLabel,
                    click: function () {
                        jQuery(this).dialog("close");
                    }
                },
            ],
            close: function (event, ui) {
                jQuery(this).remove();
            }
        });
}

function showConfirmationDialog(message,title,yesLabel, noLabel, yesCallback){
    jQuery('<div></div>').appendTo('body')
        .html('<div>'+message+'?</div>')
        .dialog({
            modal: true, title: title, zIndex: 10000, autoOpen: true,
            width: 'auto', resizable: false,
            buttons: [
                {
                    text: yesLabel,
                    click: function () {
                        jQuery(this).dialog("close");
                        yesCallback();
                    }
                },
                {
                    text: noLabel,
                    click: function () {
                        jQuery(this).dialog("close");
                    }
                }
            ],
            close: function (event, ui) {
                jQuery(this).remove();
            }
        });
}