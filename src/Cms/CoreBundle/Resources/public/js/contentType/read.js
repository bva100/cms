$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
});

$("#author-opener").on('click', function(){
    $("#author-container").toggle();
});

$("#author-cancel").on('click', function(){
    $("#author-first-name").val('');
    $("#author-last-name").val('');
    $("#author-opener-text").text('View All Authors');
    $("#author-container").hide();
});

$("#author-confirm").on('click', function(){
    if( ! $("#author-first-name").val() && ! $("#author-last-name").val() ){
        $("#author-opener-text").text('View All Authors');
    }else{
        $("#author-opener-text").text('Change Author');
    }
    $("#author-container").hide();
});