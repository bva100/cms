$(document).ready(function() {
	$("#todo-formats").addClass('todo-active');
});

$(".wizard-save").on('click', function(){
    saveTheme(getWizardContentFormatParams(), $(this));
});

function saveTheme(params, $button){
    var savePath = document.getElementById('save-path').value;
//    $button.text('Saving...').attr('disabled', true).removeClass('btn-primary');
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            alert('succss');
        }else{
            alert('failed');
        }
    });

}

function getWizardContentFormatParams(){
    params = {};
    params.contentTypeId = document.getElementById('input-contentType-id').value;
    params.siteId = document.getElementById('site-id').value;
    params.formatType = getCheckedFormatType();
    return params;
}

function getCheckedFormatType(){
    return $("#contentType-format-form .checked").attr('data-format');
}