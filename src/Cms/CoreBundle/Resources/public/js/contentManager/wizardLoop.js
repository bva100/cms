$(document).ready(function() {
    $("#todo-loop").addClass('todo-active');
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
});

$(".wizard-save").on('click', function(){
    saveLoop(getWizardLoopParams());
});

$(".switch-loop").on('click', function(event){
    event.preventDefault();
    $("#loop-selector").toggle();
});

$(".delete-loop").on('click', function(){
    if(confirm('Are you sure you want to remove this loop?')){
        deleteLoop($(this).attr('data-loop-id'), $(this));
    }
});

function deleteLoop(nodeId, $button){
    var $container = $button.parent('.loop-item');
    var deletePath = document.getElementById('delete-loop-path').value;
    var params = {};
    params.nodeId = nodeId;
    params.siteId = document.getElementById('site-id').value;
    params.contentTypeId = document.getElementById('input-contentType-id').value;
    $.post(deletePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            $container.remove();
        }else{
            alert('Unable to delete at this time. Please be sure you are logged in and try again soon.');
        }
    });
}

function saveLoop(params, $button){
    var savePath = document.getElementById('save-path').value;
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            alert('successfully created');
            window.location.reload();
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
    params.templateName = document.getElementById('input-template-name').value;
    return params;
}