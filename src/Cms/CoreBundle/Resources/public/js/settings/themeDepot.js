var addThemePath = document.getElementById('add-theme-path').value;
var siteId = document.getElementById('input-site-id').value;
var baseUrl = document.getElementById('base-url').value;

$(document).ready(function() {
	$("#settings-nav-depot").addClass('active-anchor');
});

$(".add-theme").on('click', function(){
    var $button = $(this);
    var themeOrgId = $button.attr('data-theme-org-id');
    var themeId = $button.attr('data-theme-id');
    $button.text('Saving...').attr('disabled', true);
    $.post(addThemePath, {'siteId': siteId, 'themeOrgId': themeOrgId, 'themeId': themeId}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            $("#error-container").html('');
            window.location.href = baseUrl+'/settings/theme/repo/'+siteId;
        }else{
            alert('Cannot save at this time. Please be sure you are logged in and try again. If this problem persists, please contact customer service.');
            $button.attr('disabled', false).text('Save');
        }
    }).fail(function(data, textStatus, xhr){
            $button.attr('disabled', false).removeClass('btn-info').addClass('btn-danger').text('Failed').delay(1100).queue(function() {
                $button.removeClass('btn-danger').addClass('btn-info').text('Save');
                $(this).dequeue();
            });
            $("#error-container").html(data.responseText);
        });
});