var oldTitle = document.getElementById('title').value;

$("#domain-container").on('click', function(){
    $("#slug-selector-container").hide();
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
//        var $customSlug = $('#input-custom-slug');
        $title.attr('data-slug-title', 'off');
        $('#input-custom-slug-container').show();
        $("#button-custom-slug").show();
        var text = $('#input-custom-slug').val();
    }
    updateSlug(text);
});

$("#slug-container").on('click', function(){
    $("#domain-selector-container").hide();
    $("#slug-selector-container").toggle();
});

$("#title").blur(function(){
    if($(this).attr('data-slug-title') === 'on' && document.getElementById('title').value != oldTitle){
        if(document.getElementById("state").value == 'active'){
            oldTitle = ($(this).val());
            if( ! confirm("Would you like to update the url to match this new title?")){
                return 0;
            }
        }
        updateSlug($(this).val());
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