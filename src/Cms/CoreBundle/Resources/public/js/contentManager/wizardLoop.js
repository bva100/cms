$(document).ready(function() {
    $("#todo-loop").addClass('todo-active');
});

$(".wizard-save").on('click', function(){
    saveLoop(getWizardLoopParams());
});

function saveLoop(params, $button){
    var savePath = document.getElementById('save-path').value;
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            alert('success');
        }else{
            alert('failed');
        }
    });
}

function getWizardLoopParams(){
    var params = {};
    params.nodeId = document.getElementById('input-node-id').value;
    params.domain = document.getElementById('input-domain').value;
    params.locale = document.getElementById('input-locale').value;
    params.slug = document.getElementById('input-slug').value;
    params.title = document.getElementById('input-title').value;
    params.defaultLimit = document.getElementById('input-defaultLimit').value;
    params.description = document.getElementById('input-description').value;
    return params;
}