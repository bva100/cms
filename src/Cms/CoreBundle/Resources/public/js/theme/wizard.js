var theme = $.parseJSON(document.getElementById('theme').value);

$(document).ready(function() {
    $("#input-theme-author-name").focus();
	getThemeParams();
});

function getThemeParams(){
    var objParams = {};
    objParams.id =  theme.id ? theme.id : null;
    objParams.name = document.getElementById('input-theme-name').value;
    console.log(objParams);
    return objParams
}