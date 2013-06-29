$(document).ready(function(){

    $('#features .tile').click(function(){
        iconHover($(this), 'on');
    });

    $("#features .tile").hover(function() {
        iconHover($(this), true);
    }, function() {
        iconHover($(this), false);
    });

});

function iconHover($parent, action) {
    $parentId = $parent.attr('id');
    switch($parentId){
        case 'fast-tile':
            hoverClassName = 'icon-bolt-hover';
            break;
        case 'cloud-tile':
            hoverClassName = 'icon-cloud-hover';
        default:
            break;
    }
    action ? $parent.find('i').addClass(hoverClassName) : $parent.find('i').removeClass(hoverClassName) ;
}