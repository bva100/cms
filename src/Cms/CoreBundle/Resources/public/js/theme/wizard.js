$(document).ready(function() {
    $("#input-theme-name").focus();
});

$(".todo li").on('click', function(){
    var url = '';
    var themeId = getThemeId();
    switch($(this).attr('id')){
        case 'todo-basic-info':
            url = getBaseUrl()+'/theme/wizard/'+getThemeOrgId();
            if(themeId){
                url += '?id='+themeId;
            }
            break;
        case 'todo-components':
            url = getBaseUrl()+'/theme/wizard/components/'+getThemeOrgId()+'/'+themeId;
            break;
        case 'todo-layouts':
            url = getBaseUrl() + '/theme/wizard/layouts/'+getThemeOrgId()+'/'+themeId;
            break;
        case 'todo-complete':
            openTodoComplete();
            break;
        default:
            break;
    }
    window.location.href = url;
});

$("#input-theme-name").on('change', function(){
    var name = $(this).val();
    if(name.length > 1){
        var valid = validateThemeName(name);
        if(valid){
            updateThemeName(capitalize(name));
            $(this).val(capitalize(name));
        }else{
            alert('Invalid name. Name can only contain numbers and letters without spaces.');
            $(this).val('');
        }
    }else{
        $(this).val('');
        updateThemeName('');
    }
});

function openTodoBasicInfo(){
    $(".theme-basic").show();
    $(".wizard-save").attr('data-current', 'theme-basic');
    $("#template-includes-container").hide();
}

function validateThemeName(name){
    if(!name.match(/^[0-9a-zA-Z]+$/)){
        return 0;
    }else{
        return 1;
    }
}

function capitalize(str){
    strVal = '';
    str = str.split(' ');
    for (var chr = 0; chr < str.length; chr++) {
        strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
    }
    return strVal
}

function updateThemeName(name){
    $(".theme-name").text(name);
}

function getThemeOrgId(){
    return document.getElementById('input-theme-org-id').value;
}

function getBaseUrl(){
    return document.getElementById('base-url').value;
}

function getThemeId(){
    var themeIdElem = document.getElementById('input-theme-id');
    if(themeIdElem){
        return themeIdElem.value;
    }else{
        return 0;
    }
}