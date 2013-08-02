$(document).ready(function() {
    var layoutsElem = document.getElementById('layouts-container');
    if(layoutsElem){
        $("#todo-layouts").addClass('todo-active');
    }else{
        $("#todo-components").addClass('todo-active');
    }
});

$(".wizard-save").on('click', function(){
    var params = getCodeEditorParams();
    var results = saveCodeEditor(params, $(this));
    if(results && $(this).hasClass('wizard-next')){
        window.location.href = getBaseUrl()+'/theme/wizard/layouts/'+getThemeOrgId()+'/'+params.themeId;
    }
});

$(".add-template-layout").on('click', function(event){
    event.preventDefault();
    $(".new-template-layout").toggle();
});

$(".add-new-layout").on('click', function(){
    var layoutName = document.getElementById('input-new-layout-name').value;
    addLayout(layoutName);
});

$(".delete-layout").on('click', function(){
    removeLayout($(this).attr('data-layout-name'));
});

$(".switch-template-layout").on('click', function(event){
    event.preventDefault();
    $(".layout-selector").toggle();
});

function getCodeEditorParams(){
    params = {};
    params.templateId = document.getElementById('input-template-id').value;
    params.rawCode = getCodeEditorContent();
    params.uses = JSON.stringify(getUses());
    params.themeOrgId = getThemeOrgId();
    params.themeId = document.getElementById('input-theme-id').value;
    return params;
}

function getThemeOrgId(){
    return document.getElementById('input-theme-org-id').value;
}

function getBaseUrl(){
    return document.getElementById('base-url').value;
}

function addLayout(layoutName){
    var addLayoutPath = document.getElementById('add-layout-path').value;
    var params = getCodeEditorParams();
    if(! validName(layoutName)){
        alert('Invalid layout name. Name can only contain letter and numbers without spaces.');
        return 0;
    }
    params.layoutName = layoutName;
    $.post(addLayoutPath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            window.location.href = getBaseUrl()+'/theme/wizard/layouts/'+getThemeOrgId()+'/'+getThemeId()+'?layoutName='+layoutName;
        }else{
            alert('We are unable to complete your request at this time. Please be sure you are logged in then try again. If the problem persists please contact customer service.');
        }
    }).fail(function(data, textStatus, xhr){
            alert('We are unable to complete your request at this time. Please be sure you are logged in then try again. If the problem persists please contact customer service.');
            return 0;
        });
}

function removeLayout(layoutName){
    if(layoutName === 'Single' || layoutName === 'Loop' || layoutName === 'Static'){
        alert(layoutName+' cannot be removed');
        return 0;
    }
    if(!confirm('Are you sure you want to remove '+layoutName+' ?')){
        return 0;
    }
    var removeLayoutPath = document.getElementById('remove-layout-path').value;
    var params = getCodeEditorParams();
    params.layoutName = layoutName;
    $.post(removeLayoutPath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            window.location = window.location.pathname;
        }else{
            alert('We are unable to complete your request at this time. Please be sure you are logged in then try again. If the problem persists please contact customer service.');
        }
    });
}

function validName(name){
    if(!name.match(/^[0-9a-zA-Z]+$/)){
        return 0;
    }else{
        return 1;
    }
}