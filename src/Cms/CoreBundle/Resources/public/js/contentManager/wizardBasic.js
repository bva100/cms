$("#contentType-basic-form").on('submit', function(){
    saveBasics(getContentTypeBasicParams(), $("#primary-save"));
});

$(".wizard-save").on('click', function(){
    saveBasics(getContentTypeBasicParams(), $("#primary-save"));
});

function getContentTypeBasicParams(){
    var params = {};
    params.contentTypeId = document.getElementById('input-contentType-id').value;
    params.name = document.getElementById('input-name').value;
    params.slugPrefix = document.getElementById('input-slug-prefix').value;
    params.description = document.getElementById('input-description').value;
    return params;
}

function saveBasics(params, $button){
    $button.text('Saving...').removeClass('btn-primary').attr('disabled', true);
    var savePath = document.getElementById('save-path').value;
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            $("#error-container").html('');
            window.location.href = baseUrl+'/contentManager/wizard/'+siteId+'/'+data;
        }else{
            alert('failed');
        }
    });
}