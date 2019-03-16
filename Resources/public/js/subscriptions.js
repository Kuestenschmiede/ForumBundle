var forumSubsSubmitChange = document.getElementsByClassName('forum_submit_change_button');
var index = 0;

while (index < forumSubsSubmitChange.length) {
    let currentButton = forumSubsSubmitChange.item(index);
    currentButton.addEventListener('click', function(){
        let url = currentButton.parentNode.action;
        let checkboxes = currentButton.parentNode.getElementsByTagName('input');
        jQuery.ajax(url,
            {
                method: "post",
                data: {
                    target: currentButton.dataset.target,
                    newthreads: checkboxes.item(0).checked ? 1 : 0,
                    movedthreads: checkboxes.item(1).checked ? 1 : 0,
                    deletedthreads: checkboxes.item(2).checked ? 1 : 0,
                    newposts: checkboxes.item(3).checked ? 1 : 0,
                    editedposts: checkboxes.item(4).checked ? 1 : 0,
                    deletedposts: checkboxes.item(5).checked ? 1 : 0,
                    deletesub: 0
                },
                success: function(response, status, jqXHR) {
                    showInfoDialog(response.message, response.title, 'Ok');
                }
            }
        )
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
        jQuery.ajax(url,
            {
                method: "post",
                data: {
                    target: currentButton.dataset.target,
                    newposts: checkboxes.item(0).checked ? 1 : 0,
                    editedposts: checkboxes.item(1).checked ? 1 : 0,
                    deletedposts: checkboxes.item(2).checked ? 1 : 0,
                    deletesub: 0
                },
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