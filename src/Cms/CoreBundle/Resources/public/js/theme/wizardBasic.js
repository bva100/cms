var baseUrl = document.getElementById('base-url').value;

$(document).ready(function() {
	$("#todo-basic-info").addClass('todo-active');
});

$(".wizard-save").on('click', function(){
    saveBasicTheme(getBasicThemeParams(), $(this));
});

function getBasicThemeParams(){
    params = {};
    params.themeOrgId = document.getElementById('input-theme-org-id').value;
    params.id = document.getElementById('input-theme-id').value;
    params.name = document.getElementById('input-theme-name').value;
    params.image = document.getElementById('input-theme-image').value;
    params.description = document.getElementById('input-theme-description').value;
    params.savePath = document.getElementById('save-theme-path').value;
    return params;
}

function saveBasicTheme(params, $button){
    $button.text('Saving...').attr('disabled', true);
    $.post(params.savePath, params, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            if($button.hasClass('wizard-next')){
                window.location.href = baseUrl+'/theme/wizard/components/'+params.themeOrgId+'/'+data;
            }else{
                alert('Successfully saved');
            }
        }else{
            alert('Something went wrong. Please be sure you are signed in then try again. If this problem persists, please contact customer service.');
        }
    }).fail(function(data, textStatus, xhr){
        $button.attr('disabled', false).removeClass('btn-info').addClass('btn-danger').text('Failed').delay(1100).queue(function() {
            $button.removeClass('btn-danger').addClass('btn-info').text('Save');
            $(this).dequeue();
        });
        $("#error-container").html('<div class="alert alert-danger" style="font-size: 18px;"><i class="icon-warning-sign"></i> '+data.responseText+'</div>');
    });
    $button.attr('disabled', false).removeClass('btn-info').addClass('btn-primary').text('Saved').delay(1100).queue(function() {
        $button.removeClass('btn-primary').addClass('btn-info').text('Save');
        $(this).dequeue();
    });
    $("#error-container").html('');
}