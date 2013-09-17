$(".pag-next").click(function(){
    $page = $("#page");
    $page.val( parseInt($page.val()) + 1 );
    loadData();
});

$(".pag-previous").click(function(){
    $page = $("#page");
    $page.val( parseInt($page.val())-1 );
    loadData();
});

$(".load-data-form").submit(function(event){
    event.preventDefault();
    loadData();
});

$(".load-data").click(function(){
    loadData();
});

function loadData(){
    queryStringParams = queryStringParams();
    window.location = window.location.pathname + queryStringParams;
}