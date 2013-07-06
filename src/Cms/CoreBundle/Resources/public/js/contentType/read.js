$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
    $('#tags').tagsInput();
});

$('a[disabled=disabled]').click(function(event){
    event.preventDefault();
    return false;
});

$("#category-opener").on('click', function(){
    $("#category-container").toggle();
});

$("#category-cancel").on('click', function(){
    $("#category-parent").val('');
    $("#category-sub").val('');
    $("#category-opener-text").text('View Categories');
    $("#category-container").hide();
});

$("#category-confirm").on('click', function(){
    if( ! $("#category-parent").val() && ! $("#category-sub").val() ){
        $("#category-opener-text").text('View Categories');
    }else{
        $("#category-opener-text").text('Change Category');
    }
    $("#category-container").hide();
})

$("#tag-opener").on('click', function(){
    $("#tag-container").toggle();
});

$("#tag-cancel").on('click', function(){
    $('#tags').importTags('');
    $("#tag-opener-text").text('View All Tags');
    $("#tag-container").hide();
});

$("#tag-confirm").on('click', function(){
    if( ! $('#tags').text() ){
        $("#tag-opener-text").text('View All Tags');
    }else{
        $("#tag-opener-text").text('Change Tags');
    }
    $("#tag-container").hide();
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