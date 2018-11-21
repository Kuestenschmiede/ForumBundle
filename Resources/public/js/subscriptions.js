var forumSubSubmitChange = document.getElementById('forum_submit_change_button');

forumSubSubmitChange.addEventListener('click', function(){
    let url = forumSubmitChange.parentNode.action;
    let checkboxes = forumSubSubmitChange.parentNode.getElementsByTagName('input');
    $.ajax(url,
        {
            method: "post",
            data: {
                target: forumSubSubmitChange.dataset.target,
                newthreads: checkboxes[0].checked ? 1 : 0,
                movedthreads: checkboxes[1].checked ? 1 : 0,
                deletedthreads: checkboxes[2].checked ? 1 : 0,
                newposts: checkboxes[3].checked ? 1 : 0,
                editedposts: checkboxes[4].checked ? 1 : 0,
                deletedposts: checkboxes[5].checked ? 1 : 0,
                deletesub: 0
            },
            success: function(response, status, jqXHR) {
                let obj = response.parse();
                showInfoDialog(obj.message, obj.title, 'Ok');
            }
        }
    )
});

var forumSubSubmitDelete = document.getElementById('forum_submit_delete_button');

forumSubSubmitDelete.addEventListener('click', function(){
    let url = forumSubSubmitDelete.parentNode.action;
    $.ajax(url,
        {
            method: "post",
            data: {
                target: forumSubSubmitDelete.dataset.target,
                deletesub: 1
            },
            success: function(response, status, jqXHR) {
                let obj = response.parse();
                showInfoDialog(obj.message, obj.title, 'Ok');
                let formElementDiv = forumSubSubmitDelete.parentNode.parentNode;
                let forumSubsDiv = forumSubSubmitDelete.parentNode.parentNode.parentNode;
                forumSubsDiv.removeChild(formElementDiv);
                if (formElementDiv.getElementsByClassName('sub').length === 0) {
                    forumSubsDiv.parentNode.removeChild(forumSubsDiv);
                }
            }
        }
    )
});

var threadSubSubmitChange = document.getElementById('thread_submit_change_button');

threadSubSubmitChange.addEventListener('click', function(){
    let url = threadSubSubmitChange.parentNode.action;
    let checkboxes = threadSubSubmitChange.parentNode.getElementsByTagName('input');
    $.ajax(url,
        {
            method: "post",
            data: {
                target: theadSubSubmitChange.dataset.target,
                newthreads: checkboxes[0].checked ? 1 : 0,
                movedthreads: checkboxes[1].checked ? 1 : 0,
                deletedthreads: checkboxes[2].checked ? 1 : 0,
                deletesub: 0
            },
            success: function(response, status, jqXHR) {
                let obj = response.parse();
                showInfoDialog(obj.message, obj.title, 'Ok');
            }
        }
    )
});

var threadSubSubmitDelete = document.getElementById('thread_submit_delete_button');

threadSubSubmitDelete.addEventListener('click', function(){
    let url = threadSubSubmitDelete.parentNode.action;
    $.ajax(url,
        {
            method: "post",
            data: {
                target: threadSubSubmitDelete.dataset.target,
                deletesub: 1
            },
            success: function(response, status, jqXHR) {
                let obj = response.parse();
                showInfoDialog(obj.message, obj.title, 'Ok');
                let formElementDiv = threadSubSubmitDelete.parentNode.parentNode;
                let threadSubsDiv = threadSubSubmitDelete.parentNode.parentNode.parentNode;
                threadSubsDiv.removeChild(formElementDiv);
                if (formElementDiv.getElementsByClassName('sub').length === 0) {
                    forumSubsDiv.parentNode.removeChild(threadSubsDiv);
                }
            }
        }
    )
});

function showInfoDialog(message,title,okLabel){
    $('<div></div>').appendTo('body')
        .html('<div>'+message+'?</div>')
        .dialog({
            modal: true, title: title, zIndex: 10000, autoOpen: true,
            width: 'auto', resizable: false,
            buttons: [
                {
                    text: okLabel,
                    click: function () {
                        $(this).dialog("close");
                    }
                },
            ],
            close: function (event, ui) {
                $(this).remove();
            }
        });
}