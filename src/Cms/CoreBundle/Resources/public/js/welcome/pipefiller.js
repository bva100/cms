$(document).ready(function() {
	fillPipes();
});

function fillPipes(){
    var fillers = document.getElementsByClassName('pipe-filler');
    for (var i=0; i<fillers.length; i++)
    {
        var filler = fillers[i];
        var fillBy = 1 - ( .01 * parseInt(filler.getAttribute('data-fill-by')) );
        var parent = filler.parentNode;
        var height = parent.offsetHeight;
        var topMargin = height * fillBy;
        filler.style.marginTop = topMargin + 'px';
    }
}