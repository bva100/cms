var siteId = document.getElementById('site-id').value;
var mediaAddPath = document.getElementById('media-add-path').value;

$(document).ready(function() {
	filepicker.setKey(filepickerKey);
    uploader();
});

function uploader(){
    filepicker.pickAndStore({container: 'media-uploader'},{location: 'S3', access: 'public'},function(InkBlobs){
        var mediaParams = convertInkToMediaParams(InkBlobs[0], siteId);
        mediaParams.url = getPublicMediaUrl()+mediaParams.filename;
        $.post(mediaAddPath, mediaParams, function(data, textStatus, xhr) {
            if(textStatus == 'success'){
                var mediaUploadComplete = document.getElementById('media-upload-complete');
                mediaUploadComplete.className = 'row-fluid';
                var mediaList = document.getElementById('media-list');
                mediaList.className = '';
                var li = document.createElement('li');
                li.setAttribute('class', 'media-item row-fluid');
                li.innerHTML = "<img src='"+mediaParams.url+"' class='span3' /><div class='span7'><h4>"+mediaParams.url+"</h4><p>"+mediaParams.mime+"</p></div><div class='span2'><a href='#edit' class='btn btn-info btn-block'>edit</a></div>";
                mediaList.appendChild(li);
                uploader();
            }else{
                alert('Upload failed. Please try again');
            }
        });
    });
}