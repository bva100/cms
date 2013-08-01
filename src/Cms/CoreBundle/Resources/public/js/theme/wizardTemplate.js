$(document).ready(function() {
	$("#todo-components").addClass('todo-active');
});

function getCodeEditorParams(){
    params = {};
    params.templateId = document.getElementById('input-template-id').value;
    params.rawCode = getCodeEditorContent();
    params.uses = JSON.stringify(getUses());
    params.themeOrgId = document.getElementById('input-theme-org-id').value;
    params.themeId = document.getElementById('input-theme-id').value;
    return params;
}