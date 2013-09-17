$(".switch-domain-type").on('click', function(event){
    $("#new-domain").toggle();
    $("#old-domain").toggle();
});

$(".check-namespace").on('change', function(){
    isNamespaceUnique($(this).val());
});

$(".check-domain").on('change', function(){
    isDomainUnique($(this).val());
});

function isNamespaceUnique(namespace){
    if(namespace.length === 0){
        return 0;
    }
    var path = document.getElementById('path-unique-namespace').value;
    $.get(path, {'namespace':namespace}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            data.unique ? alert('unique') : alert('not unique');
        }
    });
}

function isDomainUnique(domain){
    if(domain.length === 0){
        return 0;
    }
    var path = document.getElementById('path-unique-domain').value;
    $.get(path, {'domain':domain}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            data.unique ? alert('unique') : alert('not unique');
        }
    });
    
}