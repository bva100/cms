$(".edit-includes-action").on('click', function(event){
    event.preventDefault();
    if($(this).hasClass('edit-includes')){
        $(this).removeClass('edit-includes').addClass('done-editing-includes').text('done');
        $(".included-item").append("<p><button class='btn btn-danger delete-include-item' style='padding: 4px 10px; margin-top: 4px'><i class='icon-trash'></i> Trash</button></p>");
    }else{
        $(this).addClass('edit-includes').removeClass('done-editing-includes').text('edit');
        $(".delete-include-item").remove();
    }
    $(".delete-include-item").on('click', function(){
        var $parentLi = $(this).parents('li');
        if(confirm('Are you sure you want to remove '+$parentLi.attr('data-template-name')+' from this template?')){
            $parentLi.remove();
            saveCodeEditor(getCodeEditorParams(), $("#save-template-form button"));
        }
    });
});

$(".add-includes").on('click', function(event){
    event.preventDefault();
    $("#template-modal").modal('show');
});

$(".template-modal-show-repo").on('click', function(event){
    event.preventDefault();
    activateTemplateNav($(this));
    $("#template-modal .modal-body").hide();
    $("#template-modal-repo").show();
});

$(".template-modal-show-depot").on('click', function(event){
    event.preventDefault();
    activateTemplateNav($(this));
    $("#template-modal .modal-body").hide();
    $("#template-modal-depot").show();
});

$(".include-new-use").on('click', function(){
    var templateName = getIncludeTemplateName($(this));
    var useInclude = createNewUseInclude(templateName);
    $("#primary-include-list").append(useInclude);
    $("#template-modal").modal('hide');
    saveCodeEditor(getCodeEditorParams(), $("#save-template-form button"));
});

$(".include-new-extends").on('click', function(){
    var templateName = getIncludeTemplateName($(this));
    var oldName = getExtends();
    var extendsInclude = createNewExtendsInclude(templateName);
    var takeAction = false;
    if(!oldName){
        takeAction = true;
    }else if(oldName && confirm('Are you sure want want to extend '+templateName+' in place of '+oldName+' ?')){
        takeAction = true;
    }
    if(takeAction){
        $("#primary-extends-list").empty().html(extendsInclude);
        $("#template-modal").modal('hide');
        saveCodeEditor(getCodeEditorParams(), $("#save-template-form button"));
    }
});

function getIncludeTemplateName($this){
    return $this.parents('tr').attr('data-template-name');
}

function activateTemplateNav($this){
    $(".template-modal-nav").removeClass('template-modal-active-anchor');
    $this.addClass('template-modal-active-anchor');
}

function createNewUseInclude(name){
    return '<li class="included-item" data-include-type="use" data-template-name="'+name+'"><a href="#">'+name+'</a></li>';
}

function createNewExtendsInclude(name){
    return '<li class="included-item" data-include-type="extends" data-template-name="'+name+'"><a href="#">'+name+'</a></li>';
}

function getExtends(){
    return $("#primary-extends-list").find('.included-item').attr('data-template-name');
}

function getUses(){
    var arr = [];
    $("#primary-include-list .included-item").each(function(){
        arr.push($(this).attr('data-template-name'));
    });
    console.log(arr);
    return arr;
}