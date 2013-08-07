var siteId = document.getElementById('site-id').value;
var deletePath = document.getElementById('delete-path').value;

$(".content-type-delete").on('click', function(){
    var $tr = $(this).parents('tr');
    var contentTypeName = $(this).attr('data-content-type-name');
    var nodeId = $(this).attr('data-node-id');
    $.post(deletePath, {'siteId':siteId, 'contentTypeName':contentTypeName, 'nodeId':nodeId}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            $tr.remove();
        }else{
            alert('Unable to delete at this time. Please be sure you are signed in then try agian.');
        }
    });
});