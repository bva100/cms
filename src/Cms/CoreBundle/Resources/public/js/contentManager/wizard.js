var baseUrl = document.getElementById('base-url').value;
var siteId = document.getElementById('site-id').value;
var contentTypeId = document.getElementById('input-contentType-id').value;

$("#todo-basic-info").on('click', function(){
    var url = baseUrl+'/contentManager/wizard/'+siteId;
    if(contentTypeId){
        url += '?contentTypeId='+contentTypeId;
    }
    window.location.href = url;
});

$("#todo-formats").on('click', function(){
    window.location.href = baseUrl+'/contentManager/wizard/formats/'+siteId+'/'+contentTypeId;
});

$("#todo-loop").on('click', function(){
    window.location.href = baseUrl+'/contentManager/wizard/loop/'+siteId+'/'+contentTypeId;
});

$("#todo-static").on('click', function(){
    window.location.href = baseUrl+'/contentManager/wizard/static/'+siteId+'/'+contentTypeId;
});

function closeAllTodos(){
    $(".todo li").removeClass('todo-active');
}