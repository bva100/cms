$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
    $('#tags').tagsInput();
});

$(".load-data-form").submit(function(event){
    event.preventDefault();
    loadData();
});

$(".load-data").click(function(){
    loadData();
});

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

function queryStringParams(){
    var str = '';
    var page = document.getElementById('page').value;
    var state = document.getElementById('input-state').value;
    var startDate = document.getElementById('start-date').value;
    var endDate = document.getElementById('end-date').value;
    var tags = document.getElementById('tags').value;
    var categoryParent =  document.getElementById('category-parent').value;
    var categorySub =  document.getElementById('category-sub').value;
    var authorFirstName = document.getElementById('author-first-name').value;
    var authorLastName = document.getElementById('author-last-name').value;
    var search = document.getElementById('search').value;
    str = str+'?page='+page;
    if(state){str = str+'&state='+encodeURIComponent(state);}
    if(startDate){str = str+'&startDate='+encodeURIComponent(startDate)}
    if(endDate){str = str+'&endDate='+encodeURIComponent(endDate)}
    if(tags){str = str+'&tags='+encodeURIComponent(tags)}
    if(categoryParent){str = str+'&categoryParent='+encodeURIComponent(categoryParent)}
    if(categorySub){str = str+'&categorySub='+encodeURIComponent(categorySub)}
    if(authorFirstName){str = str+'&authorFirstName='+encodeURIComponent(authorFirstName)}
    if(authorLastName){str = str+'&authorLastName='+encodeURIComponent(authorLastName)}
    if(search){str = str+'&search='+encodeURIComponent(search)}
    return str;
}

function loadData(){
    queryStringParams = queryStringParams();
    window.location = window.location.pathname + queryStringParams;
}

$('a[disabled=disabled]').click(function(event){
    event.preventDefault();
    return false;
});

$("#category-opener").on('click', function(){
    $("#category-container").toggle();
});

$("#category-cancel").on('click', function(){
    $("#category-parent").val('');
    $("#category-sub").val('');
    $("#category-opener-text").text('View Categories');
    $("#category-container").hide();
});

$("#category-confirm").on('click', function(){
    if( ! $("#category-parent").val() && ! $("#category-sub").val() ){
        $("#category-opener-text").text('View Categories');
    }else{
        $("#category-opener-text").text('Change Category');
    }
    $("#category-container").hide();
})

$("#tag-opener").on('click', function(){
    $("#tag-container").toggle();
});

$("#tag-cancel").on('click', function(){
    $('#tags').importTags('');
    $("#tag-opener-text").text('View All Tags');
    $("#tag-container").hide();
});

$("#tag-confirm").on('click', function(){
    if( ! $('#tags').text() ){
        $("#tag-opener-text").text('View All Tags');
    }else{
        $("#tag-opener-text").text('Change Tags');
    }
    $("#tag-container").hide();
});

$("#author-opener").on('click', function(){
    $("#author-container").toggle();
});

$("#author-cancel").on('click', function(){
    $("#author-first-name").val('');
    $("#author-last-name").val('');
    $("#author-opener-text").text('View All Authors');
    $("#author-container").hide();
});

$("#author-confirm").on('click', function(){
    if( ! $("#author-first-name").val() && ! $("#author-last-name").val() ){
        $("#author-opener-text").text('View All Authors');
    }else{
        $("#author-opener-text").text('Change Author');
    }
    $("#author-container").hide();
});

$(".open-filter-options").on('click', function(){
    $("#filter-options").toggleClass('hidden-phone', function(){
        if($(this).hasClass('hidden-phone')){
            $(".open-filter-options").text('open filter options').addClass('btn-info');
        }else{
            $(".open-filter-options").text('close filter options').removeClass('btn-info');
        }
    });
});