$(document).ready(function() {
    setAssetType(getAssetType());
});

$("#label-radio-ext-css").on('click', function(){
    setAssetType('css');
    $('h1').text('Create a Stylesheet Asset');
    $("#asset-type").hide();
});

$("#label-radio-ext-js").on('click', function(){
    setAssetType('js');
    $('h1').text('Create a Javascript Asset');
    $("#asset-type").hide();
});

$("#asset-form").on('submit', function(event){
    event.preventDefault();
    saveCodeEditor(getCodeEditorParams(), $(this).find('button'));
});

function setAssetType(type){
    if(type === 'css'){
        document.getElementById('input-ext').value = 'css';
        setAssetEditorMode('css');
    }else if(type === 'js'){
        document.getElementById('input-ext').value = 'js';
        setAssetEditorMode('javascript');
    }
    return 1;
}

function getAssetType(){
    var assetTypeId = $("#asset-type input:checked").attr('id');
    if(assetTypeId === 'radio-ext-css'){
        return 'css';
    }else if(assetTypeId === 'radio-ex-js'){
        return 'js';
    }
    return '';
}

function saveCodeEditor(params, $button){
    var saveCodePath = document.getElementById('save-code-path').value;
    $button.text('Saving...').attr('disabled', true);
    $.post(saveCodePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            $("#error-container").html('<div class="alert alert-info"><i class="icon-bullhorn"></i> Saved</div>');
            $button.attr('disabled', false).removeClass('btn-info').addClass('btn-primary').text('Saved').delay(1100).queue(function() {
                $button.removeClass('btn-primary').addClass('btn-info').text('Save');
                $(this).dequeue();
            });
        }else{
            $("#error-container").html('<div class="alert alert-danger"><i class="icon-warning-sign"></i> Failed</div>');
            $button.attr('disabled', false).removeClass('btn-info').addClass('btn-primary').text('Saved').delay(1100).queue(function() {
                $button.removeClass('btn-primary').addClass('btn-info').text('Save');
                $(this).dequeue();
            });
        }
    });
}

function getCodeEditorParams(){
    var params = {};
    params.id = document.getElementById('input-id').value;
    params.siteId = document.getElementById('input-site-id').value;
    params.ext = document.getElementById('input-ext').value;
    params.name = document.getElementById('input-name').value;
    params.content = getCodeEditorContent();
    return params;
}
