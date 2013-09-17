var baseUrl = document.getElementById('base-url').value;
var selectThemePath = document.getElementById('select-theme-path').value;
var siteId = document.getElementById('input-site-id').value;

$(document).ready(function() {
	$("#settings-nav-available-themes").addClass('active-anchor');
});

$(".select-theme").on('click', function(){
    var themeId = $(this).attr('data-theme-id');
    var orgId = $(this).attr('data-theme-org-id');
    $button = $(this);
    $button.text('Saving...').attr('disabled', true);
    $.post(selectThemePath, {'siteId':siteId, 'themeOrgId':orgId, 'themeId':themeId}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            window.location.href = baseUrl+'/settings/theme/current/'+siteId;
        }else{
            alert('You request cannot be completed at this time. Please try again soon');
            $button.text('select').attr('disabled', false);
        }
    });
});