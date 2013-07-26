var mediaReadAllPath = document.getElementById('media-read-all-path').value;
var mediaSavePath = document.getElementById('media-save-path').value;
var siteId = document.getElementById('site-id').value;
var nodeId = document.getElementById('node-id').value;

$(document).ready(function() {
    loadMediaData(getMediaModalParams());
});

$(".media-modal-opener").on('click', function(){
    showMediaModal();
});

$(".media-modal-filter").on('click', function(){
    loadMediaData(getMediaModalParams());
});

$("#form-media-modal-filter").on('submit', function(event){
    event.preventDefault();
    loadMediaData(getMediaModalParams());
});

$(".insert-media").on('click', function(){
    var editor = $(this).attr('data-editor');
    var selectedMedia = getSelected();
    var title = selectedMedia.title ? selectedMedia.title : '';
    var alt = selectedMedia.alt ? selectedMedia.alt : '';
    var html = '<img src="'+selectedMedia.url+'" alt="'+alt+'" title="'+title+'"/>';
    if(editor === 'tinyMCE'){
        var mce = tinyMCE.activeEditor;
        mce.selection.setContent(html);

    }
    $("#media-modal-container").modal('hide');
});

function showMediaModal(){
    $("#media-modal-container").modal('show');
    loadMediaData(getMediaModalParams());
    return 1;
}

function getMediaModalParams(){
    mediaModalParams = {};
    mediaModalParams.limit = 18;
    var search = document.getElementById('media-modal-input-search').value;
    if(search){
        mediaModalParams.search = search;
    }
    var type = document.getElementById('media-modal-input-type').value;
    if(type){
        mediaModalParams.type = type;
    }
    return mediaModalParams;
}

function getMediaReadAllPath(format){
    if(!format){
       return mediaReadAllPath;
    }else if(format === 'json'){
        return mediaReadAllPath + '?format=json';
    }
}

$(".media-load-upload").on('click', function(event){
    event.preventDefault();
    loadUploader();
});

$(".media-load-library").on('click', function(event){
    event.preventDefault();
    loadMediaData(getMediaModalParams());
});

function loadUploader(){
    $(".media-load-upload").css('color', '#95A5A6');
    $(".media-load-library").css('color', '#16A085');
    $("#media-library-container").hide();
    $("#media-uploader-container").show();
    uploadInline("media-upload-iframe");
}

function uploadInline(iframeId){
    filepicker.pickAndStore({'container':iframeId},{location: 'S3', access: 'public'},function(InkBlobs){
        var mediaParams = convertInkToMediaParams(InkBlobs[0], siteId, nodeId ? nodeId : null);
        $.post(mediaAddPath, mediaParams, function(data, textStatus, xhr) {
            if(textStatus == 'success'){
                var title = mediaParams.title ? mediaParams.title : '';
                var alt = mediaParams.alt ? mediaParams.alt : '';
                var html = '<img src="'+mediaParams.url+'" alt="'+alt+'" title="'+title+'"/>';
                var mce = tinyMCE.activeEditor;
                mce.selection.setContent(html);
                $("#media-modal-container").modal('hide');
            }else{
                alert('Upload failed. Please try again');
            }
        });
    });
}

function loadMediaData(params){
    $("#primary-media-data-container").html('');
    $.get(getMediaReadAllPath('json'), params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            $(".media-load-upload").css('color', '#16A085');
            $(".media-load-library").css('color', '#95A5A6');
            $("#media-uploader-container").hide();
            $("#media-library-container").show();
            var mediaLoader = document.getElementById('media-modal-loader');
            var primaryMediaDataContainer = document.getElementById('primary-media-data-container');
            var mediaHTML = '';
            mediaLoader.className += ' hide';
            for (var i=0; i<data.length; i++)
            {
                mediaHTML = document.createElement('div');
                mediaHTML.innerHTML = "<img src='"+data[i].url+"' class='span2 media-preview' style='padding: 10px' data-media-json='"+JSON.stringify(data[i])+"'/>";
                primaryMediaDataContainer.appendChild(mediaHTML);
            }
            $(".media-preview").on('click', function(){
                var mediaDataJSON = $(this).attr('data-media-json');
                var mediaData = $.parseJSON(mediaDataJSON);
                selectMedia($(this));
                displayEditor(mediaData);
            });
            return 1;
        }else{
            alert('We are not able to load your media at this time. Please be sure you are logged in and try again.');
            return 0;
        }
    });
}

function displayEditor(media){
    var editor = document.getElementById('primary-media-editor');
    var html = '<div class="span12">';
    if(media.metadata.title){
       html += '<h4> Edit '+media.metadata.title+'</h4>';
       var title = media.metadata.title;
    }else{
       html += '<h4>Edit Media</h4>';
       var title = '';
    }
    if(media.metadata.alt){
       var alt = media.metadata.alt;
    }else{
       var alt = '';
    }
    html +='</div></div><div class="row-fluid"><div class="span12" style="margin-bottom: 10px;"><img src="'+media.url+'"/></div></div>';
    html +='<div class="row-fluid"><div class="span12"><form id="edit-media-form"><input type="text" id="input-media-edit-title" class="span12" style="margin: 10px 0px; font-size: 12px;" value="'+title+'" placeholder="title"/><input type="text" id="input-media-edit-alt" class="span12" style="margin: 10px 0px; font-size: 12px;" value="'+alt+'" placeholder="alt"/><button class="btn btn-info btn-block media-edit-save">Save</button></form></div></div>';
    editor.innerHTML = html;
    $("#input-media-edit-title").focus();
    $("#edit-media-form").on('submit', function(event){
        event.preventDefault();
        var newMedia = getNewMediaParams(media);
        $(".media-edit-save").text('saving...');
        $.post(mediaSavePath, newMedia, function(data, textStatus, xhr) {
            if(textStatus === 'success'){
               $(".media-edit-save").text('Save');
            }else{
               alert('Unable to edit media at this time. Please try again');
               $(".media-edit-save").text('Save');
            }
        });
    });
    return 1;
}

function getNewMediaParams(media){
    objParams = {};
    objParams.id = media.id;
    if(!media.metadata){
       media.metadata = {};
    }
    media.metadata.title = document.getElementById('input-media-edit-title').value;
    media.metadata.alt = document.getElementById('input-media-edit-title').value;
    objParams.metadata = JSON.stringify(media.metadata);
    return objParams;
}

function selectMedia($media){
    $('.media-preview').removeClass('media-selected');
    $media.addClass('media-selected');
    return 1;
}

function getSelected(){
    var $selected = $('#primary-media-data-container').find('.media-selected:first');
    var json = $selected.attr('data-media-json');
    return $.parseJSON(json);
}