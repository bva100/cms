var mediaReadAllPath = document.getElementById('media-read-all-path').value;

$(document).ready(function() {
    loadMediaData(getMediaModalParams());
});

function getMediaModalParams(){
    mediaModalParams = {};
    mediaModalParams.limit = 14;
    return mediaModalParams;
}

function getMediaReadAllPath(format){
    if(!format){
       return mediaReadAllPath
    }else if(format === 'json'){
        return mediaReadAllPath + '?format=json';
    }
}

function loadMediaData(params){
    $.get(getMediaReadAllPath('json'), params, function(data, textStatus, xhr) {
        if(textStatus === 'success'){
            var primaryMediaDataContainer = document.getElementById('primary-media-data-container');
            var mediaHTML = '';
            for (var i=0; i<data.length; i++)
            {
                mediaHTML = document.createElement('div');
                mediaHTML.innerHTML = "<img src='"+data[i].url+"'/>";
                primaryMediaDataContainer.appendChild(mediaHTML);
            }
        }else{
            alert('We are not able to load your media at this time. Please be sure you are logged in and try again.');
            return 0;
        }
    });
}