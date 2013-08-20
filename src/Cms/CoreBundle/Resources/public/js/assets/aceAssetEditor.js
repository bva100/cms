var DOMeditor = document.getElementById('input-code-content');
var codeEditor = ace.edit("input-code-content");

$(document).ready(function() {
    codeEditor.getSession().setMode("ace/mode/javascript");
    codeEditor.setTheme("ace/theme/textmate");
    codeEditor.getSession().setUseSoftTabs(true);
    codeEditor.getSession().setTabSize(2);
    DOMeditor.style.fontSize='14px';
    DOMeditor.style.fontFamily='Monaco, Consolas, "Lucida Console", monospace';
    codeEditor.commands.addCommand({
        name: 'saveFile',
        bindKey: {
            win: 'Ctrl-S',
            mac: 'Command-S',
            sender: 'editor|cli'
        },
        exec: function(env, args, request) {
            var $button = $('.save-code');
            saveCodeEditor(getCodeEditorParams(), $button);
        }
    });
});

function setAssetEditorMode(mode){
    switch(mode){
        case 'javascript':
            codeEditor.getSession().setMode("ace/mode/javascript");
            break;
        case 'css':
            codeEditor.getSession().setMode("ace/mode/css");
            break;
        default:
            break;
    }
    return 1;
}

function getCodeEditorContent(){
    return codeEditor.getValue();
}