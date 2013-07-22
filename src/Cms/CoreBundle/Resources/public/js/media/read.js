var deleteMediaPath = document.getElementById('delete-media-path').value;
var page = document.getElementById('page').value;

$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
    getParams();
});

$('.btn-checkbox-action').on('click', function(){
    var token = document.getElementById('token').value;
    var action = $(this).attr('data-action');
    var isChecked = checkIfNone();
    if(isChecked == 0){
        alert('please select a media item');
        return 0;
    }
    var ids = getCheckedIds();
    switch (action){
        case 'delete':
            ids.forEach(function(id){
                deleteMedia(id, token);
            });
            break;
        case 'edit':
            alert('not yet setup');
//            var id = ids.length == 1 ? ids[0] : ids[1] ;
//            window.location.href = baseUrl + '/node/'+id;
            break;
        default:
            alert('action not found');
            break;
    }
});

$(".pag-next").on('click', function(){
    page++;
    loadData(getParams());
});

$(".pag-previous").on('click', function(){
    page--;
    loadData(getParams());
});

$(".load-data-form").submit(function(event){
    event.preventDefault();
    loadData(getParams());
})

$(".load-data").on('click', function(){
    loadData(getParams());
});

function loadData(params){
    window.location = window.location.pathname + queryStringParams(params);
}

function queryStringParams(params){
    var str = '?page='+page;
    if(params.siteId){str = str+'&siteId='+encodeURIComponent(params.siteId)}
    if(params.type){str = str+'&type='+encodeURIComponent(params.type)}
    if(params.startDate){str = str+'&startDate='+encodeURIComponent(params.startDate)}
    if(params.endDate){str = str+'&endDate='+encodeURIComponent(params.endDate)}
    if(params.association){str = str+'&association='+encodeURIComponent(params.association)}
    if(params.search){str = str+'&search='+encodeURIComponent(params.search)}
    return str;
}

function getParams(){
    objParams = {};
    objParams.siteId = document.getElementById('site-id').value;
    objParams.type = document.getElementById('input-type').value;
    objParams.startDate = document.getElementById('input-start-date').value;
    objParams.endDate = document.getElementById('input-end-date').value;
    objParams.association = document.getElementById('input-association').value;
    objParams.search = document.getElementById('input-search').value;
    return objParams;
}

function deleteMedia(id, token){
    $.post(deleteMediaPath, {id: id, token:token}, function(data, textStatus, xhr) {
        if(textStatus == 'success'){
            $('#tr-' + id).remove();
            $("#notices").html('<div id="content-type-alert" class="alert alert-info" style="margin-top: 14px;">Deleted</div>');
            $("#content-type-alert").show(0).delay(1000).fadeOut(1000);
        }else{
           alert('Deletion failed, please try again. If the problem persists please contact customer support.');
        }
    });
}