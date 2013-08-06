var baseUrl = document.getElementById('base-url').value;
var siteId = document.getElementById('site-id').value;

$("#todo-basic-info").on('click', function(){
    var url = baseUrl+'/contentManager/wizard/'+siteId;
    var contentTypeId = document.getElementById('input-contentType-id').value;
    if(contentTypeId){
        url += '?contentTypeId='+contentTypeId;
    }
    window.location.href = url;
});

$("#todo-formats").on('click', function(){
    var contentTypeId = document.getElementById('input-contentType-id').value;
    window.location.href = baseUrl+'/contentManager/wizard/formats/'+siteId+'/'+contentTypeId;
});

function closeAllTodos(){
    $(".todo li").removeClass('todo-active');
}