$(document).ready(function() {
    $('section:first').show();
});

$(".pag-next").on('click', function(){
    var $section = $('section:visible');
    $('section').hide();
    $section.next().fadeIn();
    fillPipes();
});

$(".pag-prev").on('click', function(){
    var $section = $('section:visible');
    $('section').hide();
    $section.prev().fadeIn();
    fillPipes();
});