var savePath = document.getElementById('save-path').value;
var deletePath = document.getElementById('delete-path').value;
var readContentTypePath = document.getElementById('read-content-type-path').value;
var baseUrl = document.getElementById('base-url').value;
var mediaAddPath = document.getElementById('media-add-path').value;

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
    tinyMCE.init({
        plugins: 'link, table, save, fullscreen, charmap, code, paste, media, contextmenu',
        save_enablewhendirty: true,
        save_onsavecallback: function() {saveAJAX(getParams())},
        skin: 'flat',
        visual: false,
        statusbar: true,
        menubar: "view, edit, insert, format, table",
        toolbar: "undo, redo | bold, italic, strikethrough | alignleft, aligncenter, alignright, justify | bullist, numlist  outdent, indent |  code | charmap, link, image, cms-media",
        schema: "html5",
        selector: "#view-html",
        image_advtab: false,
        setup: function(editor) {
            editor.addButton('cms-media', {
                type: 'button',
                icon: 'image',
                style: 'float: right;',
                onclick: function(){
                    showMediaModal();
                },
            });
        },
    });
//    filepicker.setKey(filepickerKey);
});

$(".upload-media").on('click', function(event){
    event.preventDefault();
    if($(this).hasClass('upload-featured')){
        upload('featured');
    }else{
        upload('standard');
    }
});

function insertMedia(html){
    var editor = tinyMCE.activeEditor;
    editor.selection.setContent(html);
}

function upload(type){
    filepicker.pickAndStore({},{location: 'S3', access: 'public'},function(InkBlobs){
        var params = getParams();
        var mediaParams = convertInkToMediaParams(InkBlobs[0], params.siteId, params.id);
        $.post(mediaAddPath, mediaParams, function(data, textStatus, xhr) {
            if(textStatus == 'success'){
                if(type == 'featured'){
                    $(".featured-image-container").html('<img src="'+mediaParams.url+'" class="span12" id="input-featured-image">');
                }else{
                    alert('added' + mediaParams.url);
                }
                if(params.id){
                    saveAJAX(getParams());
                }
            }else{
                alert('Upload failed. Please try again');
            }
        });
    });
}

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

$(".edit-fields").on('click', function(event){
    event.preventDefault();
});

$(".field-key-value-pairs-container-toggler").on('click', function(event){
    event.preventDefault();
    $('.field-key-value-pairs-container').show();
    $(this).hide();
    toggleAddField();
});

$(".add-field-toggler").on('click', function(event){
    event.preventDefault();
    toggleAddField();
});

$(".edit-fields").on('click', function(event){
    event.preventDefault();
    if($(this).hasClass('btn-inverse')){
        $(this).removeClass("btn-inverse").addClass('btn-primary').html('<i class="icon-save"> Done</i>');
        $(".add-field-toggler").hide();
        $("#add-field-footer-btn-container .save-node").hide();
        $(".field-key-input").attr('type', 'text').parent('div').removeClass('span4').addClass('span10');
        $(".field-key").hide();
        $(".field-value").hide();
        $(".field-key-value-pairs").append('<button class="btn btn-danger remove-field" style="position: relative; left: 10px;"><i class="icon-trash"> Delete</i></button>');
        $(".remove-field").on('click', function(event){
            event.preventDefault();
            if(confirm('Are you sure you want to remove this field?')){
                var $container = $(this).parent('div');
                var field = {'key':$container.attr('data-field-key'), 'value':$container.attr('data-field-value')}
                $container.remove();
            }
        });
    }else{
        $(this).removeClass('btn-primary').addClass('btn-inverse').html('<i class="icon-edit"></i> Edit');
        $(".add-field-toggler").show();
        $("#add-field-footer-btn-container .save-node").show();
        $(".field-key-input").attr('type', 'hidden').parent('div').removeClass('span10').addClass('span4');
        $(".field-key").show();
        $(".field-value").show();
        $(".remove-field").remove();
    }
});

$(".field-key-input").on('blur', function(){
    $(this).next().text($(this).val());
});

$(".create-field-key").on('click', function(event){
    event.preventDefault();
    var key = document.getElementById('input-new-field-key');
    var field = createNewField(key.value);
    $(".field-key-value-pairs-inner").append(field);
    key.value = '';
    $(".add-field").hide();
});

$(".confirm-add-category").on('click', function(){
    addCategory();
});

$("#add-category-form").on('submit', function(event){
    event.preventDefault();
    addCategory();
});

$(".category-toggle-icon").on('click', function(){
    var parentStr = $(this).attr('data-parent');
    switchCategorySub(parentStr);
    switchToggleIcon($(this));
});

$(".category-parent-name").on('click', function(){
    var $td = $(this).next();
    switchCategorySub($td.attr('data-parent'));
    switchToggleIcon($td);
});

$(".save-node").on('click', function(){
    saveAJAX(getParams());
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

$(".category-toggle-icon").on('click', function(){
    var parentStr =  $(this).attr('data-parent').toLowerCase();
    var dataContainer = $(".category-data tr[data-parent='"+parentStr+"']");
    var data = '';
    var results = $("#table-category-clone tr[data-parent='"+parentStr+"']").html();
    dataContainer.html(data);
});

function switchCategorySub(parentStr){
    $('.parent-'+parentStr).toggle();
}

function switchToggleIcon($td){
    var state = $td.attr('data-state');
    if(state === 'opened'){
        $td.html('<i class="icon-chevron-sign-left"></i>');
        $td.attr('data-state', 'closed');
    }else{
        $td.html('<i class="icon-chevron-sign-down"></i>');
        $td.attr('data-state', 'opened');
    }
}

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
    objParams['description'] = document.getElementById('input-description').value;
    objParams['viewHtml'] = tinyMCE.activeEditor.getContent();
    objParams['viewText'] = tinyMCE.activeEditor.getContent({format: 'raw'});
    objParams['description'] = document.getElementById('input-description').value;
    objParams['authorId'] = document.getElementById('input-author-id').value;
    objParams['authorFirstName'] = document.getElementById('input-author-first-name').value;
    objParams['authorLastName'] = document.getElementById('input-author-last-name').value;
    objParams['authorImage'] = document.getElementById('input-author-image').value;
    objParams['featuredImage'] = $("#input-featured-image").attr('src');
    objParams['categoriesJSON'] = JSON.stringify(getCategories());
    objParams['tagsJSON'] = JSON.stringify(getTags());
    objParams['fieldsJSON'] = JSON.stringify(getFields());
    return objParams;
}

function getCategories(){
    var categories = [];
    var result = {};
    $("#categories-holder .checked").each(function(){
        var categoryArray = $(this).attr('data-category').split('-');
        if(categoryArray.length > 1){
            result = {'parent': categoryArray[0], 'sub': categoryArray[1]};
        }else{
            result = {'parent': categoryArray[0]};
        }
        categories.push(result);
    });
    return categories;
}

function getRawCategories(){
    var categories = [];
    $("#categories-holder .checked").each(function(){
        categories.push($(this).attr('data-category'));
    });
    return categories;
}

function addCategory(){
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
}

function getTags(){
    return document.getElementById('tags').value.split(',');
}

function createNewField(key){
    return '<div class="row-fluid field-key-value-pairs"><div class="span4"><input type="hidden" class="field-key-input" value="'+key+'"/><span class="field-key">'+key+'</span></div><div class="span8"><input type="text" class="span12 field-value" placeholder="add a value for '+key+'"/></div></div>';
}

function getFields(){
    var fields = [];
    var obj = {};
    $(".field-key-value-pairs").each(function(){
        obj.key = $(this).find('.field-key').text();
        obj.value = $(this).find('.field-value').val();
        fields.push(obj);
        obj = {};
    });
    return fields;
}

function toggleAddField(){
    var $addField = $(".add-field");
    $addField.toggle();
    $("#input-new-field-key").focus();
}

function saveAJAX(params){
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            $("#notice-container").html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" style="color: white">&times;</button><i class="icon-bullhorn" style="margin-right: 5px;"></i> Save Complete</div>');
        }else{
            alert('Unable to save. Please be sure you are logged in and try again. If problem persists please contact customer services.');
            return 0;
        }
    });
}

function save(params, urlAffix){
    if(!urlAffix){
        urlAffix = '';
    }
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            window.location.href = savePath + '/' + data + urlAffix;
        }else{
            alert('Unable to save. Please be sure you are logged in and try again. If problem persists please contact customer services.');
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