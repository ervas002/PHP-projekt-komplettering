$(document).ready(function(){
    var errors = document.getElementById("errors");
    if(errors.length > 0){
        alert(errors);
    }

    var enableEditCommentButtons = document.getElementsByClassName("enableEditButton");
    for(var i = 0; i < enableEditCommentButtons.length; i++){
        enableEditCommentButtons[i].onclick = function(){
            var editDivId = this.id.replace("enableEditButton","");
            document.getElementById(editDivId + "editDiv").style.display = 'inherit';
        };
    }

    var enableEditPostButtons = document.getElementsByClassName("enablePostEditButton");
    for(var i = 0; i < enableEditPostButtons.length; i++){
        enableEditPostButtons[i].onclick = function(){
            var editDivId = this.id.replace("enablePostEditButton","");
            document.getElementById(editDivId + "editPostDiv").style.display = 'inherit';
        };
    }
});

var globalTestVariabel = 100;
var editTextareaId = "";
var deleteButtonId = "";