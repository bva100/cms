$(document).ready(function() {
	var alertOn = $(".alert").length > 0;
    if(alertOn){
        $("#notice-container").show(0).delay(1000).fadeOut(1000);
    }
});

$("#title").blur(function(){
    var text = $(this).val();
    updateSlug(text);
});

function updateSlug(text){
    $("#slug-container").text(text);
    $("#slug").val(text);
}