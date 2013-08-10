$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
});

$("#input-ext").on('change', function(){
    loadData(getAssetLoopParams());
});

$("#search-form").on('submit', function(event){
    event.preventDefault();
    loadData(getAssetLoopParams());
});

$(".page-previous").on('click', function(event){
    var params = getAssetLoopParams();
    if(params.page > 1){
        params.page -= 1;
    }
    loadData(params);
});

$(".page-next").on('click', function(){
    var params = getAssetLoopParams();
    params.page += 1;
    loadData(params);
});

function getAssetLoopParams(){
    var params = {};
    params.page = parseInt(document.getElementById('input-page').value);
    params.type = document.getElementById('input-ext').value;
    params.limit = document.getElementById('input-limit').value;
    params.search = document.getElementById('search').value;
    return params;
}

function loadData(params){
    var url = document.getElementById('path').value;
    url += '?page='+params.page;
    if(params.type){
        url += '&type='+params.type;
    }
    if(params.limit){
        url += '&limit='+params.limit;
    }
    if(params.search){
        url += '&search='+encodeURIComponent(params.search);
    }
    window.location.href = url;
}