$(document).ready(function() {
	$("todo-static").addClass('todo-active');
});

$(".wizard-save").on('click', function(){
    saveCodeEditor(getCodeEditorParams());
});

function getCodeEditorParams(){
    var params = {};
    params.nodeId = document.getElementById('input-node-id').value;
    params.domain = document.getElementById('input-domain').value;
    params.locale = document.getElementById('input-locale').value;
    params.slug = document.getElementById('input-slug').value;
    params.title = document.getElementById('input-title').value;
    params.description = document.getElementById('input-description').value;
    params.content =  getCodeEditorContent();
    return params;
}