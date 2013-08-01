var DOMeditor = document.getElementById('input-code-content');
var codeEditor = ace.edit("input-code-content");

$(document).ready(function() {
    codeEditor.setTheme("ace/theme/textmate");
    codeEditor.getSession().setMode("ace/mode/html");
    codeEditor.getSession().setUseSoftTabs(true);
    codeEditor.getSession().setTabSize(2);
    DOMeditor.style.fontSize='14px';
    DOMeditor.style.lineHeight='1.4';
    DOMeditor.style.fontFamily='DejaVu Sans Mono';
    codeEditor.commands.addCommand({
        name: 'saveFile',
        bindKey: {
            win: 'Ctrl-S',
            mac: 'Command-S',
            sender: 'editor|cli'
        },
        exec: function(env, args, request) {
            var $button = $("#save-template-form").find('button');
            saveCodeEditor(getCodeEditorParams(), $button);
        }
    });
});

$("#save-template-form").on('submit', function(event){
    event.preventDefault();
    saveCodeEditor(getCodeEditorParams(), $(this).find('button'));
});

function getCodeEditorContent(){
    return codeEditor.getValue();
}

function getCodeEditorParams(){
    var type = getCodeEditorType();
    var params = {};
    params.id = document.getElementById('template-id').value;
    params.type = type;
    params.rawCode = getCodeEditorContent();
    if(type === 'template' || type === 'templateTheme' || type === 'menu'){
        params.name = document.getElementById('input-template-name').value;
        params.state = document.getElementById('input-template-state').value;
        params.extends = getExtends();
        params.uses = JSON.stringify(getUses());
    }
    if(type === 'template' || type === 'menu'){
        params.siteId = document.getElementById('site-id').value;
    }
    if(type === 'templateTheme'){
        params.themeName = document.getElementById('input-theme-name').value;
        params.themeOrgId = document.getElementById('input-theme-org-id').value;
        params.themeId = document.getElementById('input-theme-id').value;
    }
    return params;
}

function saveCodeEditor(params, $button){
    $button.text('Saving...').attr('disabled', true);
    $.post(getCodeSavePath(), params, function(data, textStatus, xhr) {
        if(xhr.status == 200){
            $("#error-container").html('');
            $button.attr('disabled', false).removeClass('btn-info').addClass('btn-primary').text('Saved').delay(1100).queue(function() {
                $button.removeClass('btn-primary').addClass('btn-info').text('Save');
                $(this).dequeue();
            });
        }else{
            alert('Cannot save at this time. Please be sure you are logged in and try again. If this problem persists, please contact customer service.');
            $button.attr('disabled', false).text('Save');
        }
    }).fail(function(data, textStatus, xhr){
        $button.attr('disabled', false).removeClass('btn-info').addClass('btn-danger').text('Failed').delay(1100).queue(function() {
            $button.removeClass('btn-danger').addClass('btn-info').text('Save');
            $(this).dequeue();
        });
        $("#error-container").html('<div class="alert alert-danger" style="font-size: 18px;"><i class="icon-warning-sign"></i> '+data.responseText+'</div>');
    });
}

function getCodeSavePath(){
    return document.getElementById('code-save-path').value;
}

function getCodeEditorType(){
    return document.getElementById('input-template-type').value;
}