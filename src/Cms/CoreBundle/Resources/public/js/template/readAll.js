function queryStringParams(){
    var str = '';
    var page = document.getElementById('page').value;
    var search = document.getElementById('search').value;
    str = str+'?page='+page;
    if(search){str = str+'&search='+encodeURIComponent(search)}
    return str;
}