var theme = $.parseJSON(document.getElementById('theme').value);
var themeNamespace = document.getElementById('theme-namespace').value;

$(document).ready(function() {
    $("#input-theme-author-name").focus();
	getThemeParams();
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
    // validate name here
    updateThemeName($(this).val());
});

function updateThemeName(name){
    $(".theme-name").text(name);
}

function updateThemeNamespace(name){
    themeNamespace = name;
}

function openTodoBasicInfo(){
    $(".theme-basic").show();
    $(".wizard-save").attr('data-current', 'theme-basic');
    $("#template-includes-container").hide();
}

function openTodoComponents(){
    var params = getThemeParams();
    alert(params);
    if(! params.name || ! params.authorName){
        alert('Please add the required Basic Information fields and press the "save and proceed" button before moving onto Components');
        openTodoBasicInfo();
        selectTodoItem($('#todo-basic-info'));
        return 0;
    }
    $("#template-includes-container").show();
    $(".wizard-save").attr('data-current', 'theme-basic');
    $(".theme-components").show();
}

function getThemeParams(){
    var objParams = {};
    objParams.id =  theme.id ? theme.id : null;
    objParams.name = document.getElementById('input-theme-name').value;
    objParams.authorName = document.getElementById('input-theme-author-name').value;
    objParams.components = {};
    objParams.components.name = themeNamespace+':'
    return objParams
}

function selectTodoItem($item){
    $(".todo li").removeClass("todo-active");
    $item.addClass("todo-active");
    return 1;
}

function closeWizardBody(){
    $(".wizard-body-item").hide();
}