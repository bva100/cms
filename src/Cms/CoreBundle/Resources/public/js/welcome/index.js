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
    parentId = $parent.attr('id');
    switch(parentId){
        case 'fast-tile':
            hoverClassName = 'icon-bolt-hover';
            break;
        case 'cloud-tile':
            hoverClassName = 'icon-cloud-hover';
            break;
        case 'multilingual-tile':
            hoverClassName = 'icon-globe-hover';
            break;
        case 'api-tile':
            hoverClassName = 'icon-magic-hover';
            break;
        case 'cog-tile':
            hoverClassName = 'icon-cog-hover';
            break;
        case 'theme-tile':
            hoverClassName = 'icon-heart-hover';
            break;
        default:
            hoverClassName = '';
            break;
    }
    if(hoverClassName){
        action ? $parent.find('i').addClass(hoverClassName) : $parent.find('i').removeClass(hoverClassName) ;
    }
}