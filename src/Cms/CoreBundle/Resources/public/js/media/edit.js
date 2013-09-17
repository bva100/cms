var siteId = document.getElementById('site-id').value;
var id = document.getElementById('id').value;
var savePath = document.getElementById('save-path').value;

$(".save").on('click', function(){
    saveAjax(getParams());
});

function getParams(){
    objParams = {};
    objParams.siteId = siteId;
    objParams.id = id;
    objParams.metadata = {};
    objParams.metadata.title = document.getElementById('input-title').value;
    objParams.metadata.alt = document.getElementById('input-alt').value;
    objParams.metadata = JSON.stringify(objParams.metadata);
    return objParams;
}

function saveAjax(params){
    $.post(savePath, params, function(data, textStatus, xhr) {
        if(textStatus == 'success'){
            $("#notices").html('<div id="content-type-alert" class="alert alert-info" style="margin-top: 14px;"><i class="icon-bullhorn"></i> Save Complete</div>');
            $("#content-type-alert").show(0).delay(1000).fadeOut(1000);
        }else{
            alert('We cannot complete your request at this time. Please try again soon. If the problem persists, please contact customer service.');
        }
    });
}