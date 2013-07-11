var savePath = document.getElementById('save-path').value;
var deletePath = document.getElementById('delete-path').value;
var readContentTypePath = document.getElementById('read-content-type-path').value;
var baseUrl = document.getElementById('base-url').value;

$(document).ready(function() {
    $('select').selectpicker();
    $(".select .dropdown-toggle").addClass('btn-info');
    $(".dropdown-menu").addClass('dropdown-inverse');
    $(".dropdown-arrow").addClass('dropdown-arrow-inverse');
    $('#tags').tagsInput();
	var alertOn = $(".alert").length > 0;
    if(alertOn){
        $("#inner-notice-container").show(0).delay(1000).fadeOut(1000);
    }
});

$("#state-container-opener").on('click', function(){
    $("#state-container").toggle();
});

$("#publish-date-container-opener").on('click', function(){
    $("#publish-date-container").toggle();
});

$("#input-state").on('change', function(){
    $("#submit-input-state").show();
});

$("#submit-input-state").on('click', function(){
    save(getParams());
});

$("#add-category-opener").on('click', function(){
    $("#add-category-container").toggle();
});

$("#cancel-add-category").on('click', function(){
    clearAddCategory();
});

$(".category-toggle-icon").on('click', function(){
    test = $(this).parents('.category-parent');
    test.find('li').hide();
//        .find("[data-parent='" + parent + "']").hide();
});

$(".category-parent").on('click', function(){
    var parent = $(this).attr('data-parent');
    var list = $("ul").find("[data-parent='" + parent + "']").show();
    $(this).appendTo(html(list));
});

$(".confirm-add-category").on('click', function(){
    var path = document.getElementById('add-category-path').value;
    params = getParams();
    catParams = {};
    catParams.siteId = params.siteId;
    catParams.id = document.getElementById('input-content-type-id').value;
    catParams.parent = document.getElementById('add-category-parent').value;
    catParams.sub = document.getElementById('add-category-sub').value;
    $.post(path, catParams, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            save(params, '#categories-holder');
        }else{
            alert('Unable to add new category. Please try again.');
        }
    });
});

$(".save-node").on('click', function(){
    save(getParams());
});

$(".preview-node").on('click', function(){
    var params = getParams();
    saveAndPreview(params);
});

$(".delete-node").on('click', function(){
    if(confirm('Are you sure you want to permanently delete this item?')){
        var token = document.getElementById('input-token').value;
        var id = document.getElementById('input-id').value;
        $.post(deletePath, {'id':id, 'token':token}, function(data, textStatus, xhr) {
            if(textStatus === 'success'){
                window.location.href = readContentTypePath;
            }else{
                alert('Unable to delete. Please try again.');
            }
        });
    }
});

$(".publish-node").on('click', function(){
    params = getParams();
    params.state = 'active';
    saveAndPreview(params);
});

function clearAddCategory(){
    $("#add-category-parent").val('');
    $("#add-category-sub").val('');
    $("#add-category-container").hide();
}

function getFullSlug(params){
    var slug = params.slug;
    var slugprefix = params.slugPrefix;
    if(slug.indexOf(slugprefix) !== -1){
        return params.slug;
    }else{
        return params.slugPrefix + params.slug;
    }
}

function getUrl(params){
    return '/'+getFullSlug(params);
}

function getParams(){
    objParams = {};
    objParams['token'] = document.getElementById('input-token').value;
    objParams['id'] = document.getElementById('input-id').value;
    objParams['state'] = document.getElementById('input-state').value;
    objParams['siteId'] = document.getElementById('input-site-id').value;
    objParams['contentTypeName'] = document.getElementById('input-content-type-name').value;
    objParams['format'] = document.getElementById('input-format').value;
    objParams['templateName'] = document.getElementById('input-template-name').value;
    objParams['slugPrefix'] = document.getElementById('input-slug-prefix').value;
    objParams['domain'] = document.getElementById('domain').value;
    objParams['slug'] = document.getElementById('slug').value;
    objParams['title'] = document.getElementById('title').value;
    objParams['viewHtml'] = document.getElementById('view-html').value;
    objParams['description'] = document.getElementById('input-description').value;
    objParams['authorId'] = document.getElementById('input-author-id').value;
    objParams['authorFirstName'] = document.getElementById('input-author-first-name').value;
    objParams['authorLastName'] = document.getElementById('input-author-last-name').value;
    objParams['authorImage'] = document.getElementById('input-author-image').value;
    return objParams;
}

function save(params, urlAffix){
    if(!urlAffix){
        urlAffix = '';
    }
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            window.location.href = savePath + '/' + data + urlAffix;
        }else{
            alert('Unable to save. Please try again. If problem persists please contact customer services.');
        }
    });
}

function saveAndPreview(params){
    if(!params.slug){
        alert('You must include a title before seeing a preview');
        return 0;
    }
    var url = getUrl(params);
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            window.location.href = savePath + '/' + data;
            window.open(url,'_blank');
        }else{
            alert('Unable to display preview. Please try again.');
            return 0;
        }
    });
}

function addNewCategory(catParams){
    var title = catParams.parent;
    if(catParams.sub){

    }
    var catNonce = catParams.parent;
    if(catParams.sub){
        catNonce = catNonce+'-'+catParams.sub;
    }
    var newCat = document.createElement('<label class="checkbox category-checkbox" for="checkbox-'+catNonce+'"><input type="checkbox"  data-toggle="checkbox" name="category-checkbox" id="checkbox-'+catNonce+'"/>{{ category.parent|title }} {% if category.sub is defined %}- {{ category.sub|title }}{% endif %}</label>')
}