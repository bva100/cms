$("#title").blur(function(){
    var text = $(this).val();
    updateSlug(text);
});

function updateSlug(text){
    $("#slug-container").text(text);
    $("#slug").val(text);
}