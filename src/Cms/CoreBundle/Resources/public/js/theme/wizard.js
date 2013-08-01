var theme = $.parseJSON(document.getElementById('theme').value);

$(document).ready(function() {
    $("#input-theme-name").focus();
});

$(".todo li").on('click', function(){
    selectTodoItem($(this));
    closeWizardBody();
    switch($(this).attr('id')){
        case 'todo-basic-info':
            openTodoBasicInfo();
            break;
        case 'todo-components':
            openTodoComponents();
            break;
        case 'todo-layouts':
            openTodoLayouts();
            break;
        case 'todo-complete':
            openTodoComplete();
            break;
        default:
            break;
    }
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

function openTodoBasicInfo(){
    $(".theme-basic").show();
    $(".wizard-save").attr('data-current', 'theme-basic');
    $("#template-includes-container").hide();
}

function openTodoComponents(){
    var params = getThemeParams();
    setCodeSavePath('components');
    if(! params.name){
        alert('Please add the name of this theme before proceeding');
        openTodoBasicInfo();
        selectTodoItem($('#todo-basic-info'));
        $('#input-theme-name').focus();
        return 0;
    }
    $("#template-includes-container").show();
    $(".wizard-save").attr('data-current', 'theme-components');
    $(".theme-components").show();
}

function getThemeParams(){
    var objParams = {};
    objParams.id =  theme.id ? theme.id : null;
    objParams.name = document.getElementById('input-theme-name').value;
    objParams.components = {};
    objParams.layouts = {};
    return objParams
}

function selectTodoItem($item){
    $(".todo li").removeClass("todo-active");
    $item.addClass("todo-active");
    return 1;
}

function setCodeSavePath(pathname){
    var path = '';
    switch(pathname){
        case 'components':
            path =  document.getElementById('components-save-path').value;
            break;
        default:
            break;
    }
    document.getElementById('code-save-path').value = path;
    $("#code-save-path").val(path);
    return 1;
}

function closeWizardBody(){
    $(".wizard-body-item").hide();
}

function save(params){
    delete objParams.components;
}