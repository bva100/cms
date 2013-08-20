var isTouch = 'ontouchstart' in document.documentElement;

$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
    $('#tags').tagsInput();
    if(isTouch){
        $(".quick-action-container").css('min-height', '0px');
    }
});

$(".label-loop").popover({
    'placement':'left',
    'trigger': 'hover',
    'html':true,
    'content': function(){
        return $(this).find('.list-container').html();
    },
});

if(!isTouch){
    $(".checkbox-container td").mouseover(function(){
        $(this).find('.quick-action').show();
    });
    $(".checkbox-container td").mouseout(function(){
        $(this).find('.quick-action').hide();
    });
}

$('.quick-delete-action').on('click', function(event){
    event.preventDefault();
    var id = $(this).attr('data-id');
    if(confirm('Are you sure you want to delete this?')){
        deleteNode(id);
    }                                                                                                                                                                                                                                                                                                                                                                        
});

$('.btn-checkbox-action').on('click', function(){
    token = $("#token").val();
    baseUrl = $("#baseUrl").val();
    var action = $(this).attr('data-action');
    var isChecked = checkIfNone();
    if(isChecked == 0){
        alert('please select an item');
        return 0;
    }
    var ids = getCheckedIds();
    switch (action){
        case 'delete':
            ids.forEach(function(id){
                deleteNode(id, token);
            });
            break;
        case 'edit':
            var id = ids.length == 1 ? ids[0] : ids[1] ;
            window.location.href = baseUrl + '/node/'+id;
            break;
        default:
            alert('action not found');
            break;
    }
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

function deleteNode(id, token){
    $.post('/node/delete', {id: id, token: token}, function(data, textStatus, xhr) {
        if(textStatus == 'success'){
            $('#tr-' + id).remove();
            $("#notices").html('<div id="content-type-alert" class="alert alert-info">Deleted</div>');
            $("#content-type-alert").show(0).delay(1000).fadeOut(1000);
        }else{
            alert('Unsuccessful delete. Please try again.');
        }
    });
}

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
            $(".open-filter-options").text('open filter options');
        }else{
            $(".open-filter-options").text('close filter options').removeClass('btn-info');
        }
    });
});