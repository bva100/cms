$(".edit-includes-action").on('click', function(event){
    event.preventDefault();
    if($(this).hasClass('edit-includes')){
        $(this).removeClass('edit-includes').addClass('done-editing-includes').text('done');
        $(".included-item").append("<p><button class='btn btn-danger delete-include-item' style='padding: 4px 10px; margin-top: 4px'><i class='icon-trash'></i> Trash</button></p>");
        $(".delete-include-item").on('click', function(){
            var $parentLi = $(this).parents('li');
            if(confirm('Are you sure you want to remove '+$parentLi.attr('data-template-name')+' from this template?')){
                $parentLi.remove();
            }
        });
    }else{
        $(this).addClass('edit-includes').removeClass('done-editing-includes').text('edit');
        $(".delete-include-item").remove();
    }
});

$(".add-includes").on('click', function(event){
    event.preventDefault();
    $("#template-modal").modal('show');
});

$(".include-new-use").on('click', function(){
    var templateName = $(this).parents('tr').attr('data-template-name');
    var useInclude = createNewUseInclude(templateName);
    $("#primary-include-list").append(useInclude);
    $("#template-modal").modal('hide');
});

function createNewUseInclude(name){
    return '<li class="included-item" data-include-type="use" data-template-name="'+name+'"><a href="#">'+name+'</a></li>';
}

function getExtends(){
    return $("#primary-extends-list").find('.included-item').attr('data-template-name')
}

function getUses(){
    return $("#primary-include-list").find('.included-item').attr('data-template-name');
}