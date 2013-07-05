$("#domain-container").on('click', function(){
    $("#domain-selector-container").toggle();
});

$(".radio-domain").click(function(){
    var domain = $(this).find('input').attr('data-domain');
    updateDomain(domain);
    $("#domain-selector-container").hide(400);
});

$(".radio-slug").on('click', function(){
    var slugType = $(this).attr('data-slug-type');
    var $title = $('#title');
    if(slugType === 'title'){
        $title.attr('data-slug-title','on');
        var text = $title.val();
        $("#slug-selector-container").hide();
    }else{
        var $customSlug = $('#input-custom-slug');
        $title.attr('data-slug-title', 'off');
        $customSlug.show();
        $("#button-custom-slug").show();
        var text = $customSlug.val();
    }
    updateSlug(text);
});

$("#slug-container").on('click', function(){
    $("#slug-selector-container").toggle();
});

$("#title").blur(function(){
    if($(this).attr('data-slug-title') === 'on'){
        var text = $(this).val();
        updateSlug(text);
    }
});

$('#input-custom-slug').blur(function(){
    var text = $(this).val();
    updateSlug(text);
    $("#slug-selector-container").hide();
})

function updateSlug(text){
    text = text.replace(/\s+/g, '-').toLowerCase();
    $("#slug-container").text(text);
    $("#slug").val(text);
}

function updateDomain(domain){
    $("#domain-container").text(domain);
    $("#domain").val(domain);
}