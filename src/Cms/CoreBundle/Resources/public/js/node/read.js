$(document).ready(function() {
	var alertOn = $(".alert").length > 0;
    if(alertOn){
        $("#notice-container").show(0).delay(1000).fadeOut(1000);
    }
});