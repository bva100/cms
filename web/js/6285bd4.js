var DOMeditor=document.getElementById("input-code-content");var codeEditor=ace.edit("input-code-content");$(document).ready(function(){codeEditor.setTheme("ace/theme/textmate");codeEditor.getSession().setMode("ace/mode/html");codeEditor.getSession().setUseSoftTabs(true);codeEditor.getSession().setTabSize(2);DOMeditor.style.fontSize="14px";DOMeditor.style.fontFamily='Monaco, Consolas, "Lucida Console", monospace';codeEditor.commands.addCommand({name:"saveFile",bindKey:{win:"Ctrl-S",mac:"Command-S",sender:"editor|cli"},exec:function(env,args,request){var $button=$("#save-template-form").find("button");saveCodeEditor(getCodeEditorParams(),$button)}})});$("#save-template-form").on("submit",function(event){event.preventDefault();saveCodeEditor(getCodeEditorParams(),$(this).find("button"))});function getCodeEditorContent(){return codeEditor.getValue()}function getCodeEditorParams(){var type=getCodeEditorType();var params={};params.id=document.getElementById("template-id").value;params.type=type;params.rawCode=getCodeEditorContent();if(type==="template"||type==="templateTheme"||type==="menu"){params.name=document.getElementById("input-template-name").value;params.state=document.getElementById("input-template-state").value;params.extends=getExtends();params.uses=JSON.stringify(getUses())}if(type==="template"||type==="menu"){params.siteId=document.getElementById("site-id").value}if(type==="templateTheme"){params.themeName=document.getElementById("input-theme-name").value;params.themeOrgId=document.getElementById("input-theme-org-id").value;params.themeId=document.getElementById("input-theme-id").value}return params}function saveCodeEditor(params,$button){$button.text("Saving...").attr("disabled",true);$.post(getCodeSavePath(),params,function(data,textStatus,xhr){if(xhr.status==200){$("#error-container").html("");if(!params.id&&!params.layoutName){var baseUrl=document.getElementById("base-url").value;window.location.href=baseUrl+"/template/"+params.siteId+"/"+data}$button.attr("disabled",false).removeClass("btn-info").addClass("btn-primary").text("Saved").delay(1100).queue(function(){$button.removeClass("btn-primary").addClass("btn-info").text("Save");$(this).dequeue()});return 1}else{alert("Cannot save at this time. Please be sure you are logged in and try again. If this problem persists, please contact customer service.");$button.attr("disabled",false).text("Save");return 0}}).fail(function(data,textStatus,xhr){$button.attr("disabled",false).removeClass("btn-info").addClass("btn-danger").text("Failed").delay(1100).queue(function(){$button.removeClass("btn-danger").addClass("btn-info").text("Save");$(this).dequeue()});$("#error-container").html(createCodeEditorAlert(data.responseText));return 0});return 1}function createCodeEditorAlert(response){return'<div class="alert alert-danger" style="font-size: 18px;"><i class="icon-warning-sign"></i>'+response+"</div>"}function getCodeSavePath(){return document.getElementById("code-save-path").value}function getCodeEditorType(){return document.getElementById("input-template-type").value}
$(".edit-includes-action").on("click",function(event){event.preventDefault();if($(this).hasClass("edit-includes")){$(this).removeClass("edit-includes").addClass("done-editing-includes").text("done");$(".included-item").append("<p><button class='btn btn-danger delete-include-item' style='padding: 4px 10px; margin-top: 4px'><i class='icon-trash'></i> Trash</button></p>")}else{$(this).addClass("edit-includes").removeClass("done-editing-includes").text("edit");$(".delete-include-item").remove()}$(".delete-include-item").on("click",function(){var $parentLi=$(this).parents("li");if(confirm("Are you sure you want to remove "+$parentLi.attr("data-template-name")+" from this template?")){$parentLi.remove();saveCodeEditor(getCodeEditorParams(),$("#save-template-form button"))}})});$(".add-includes").on("click",function(event){event.preventDefault();$("#template-modal").modal("show")});$(".template-modal-show-repo").on("click",function(event){event.preventDefault();activateTemplateNav($(this));$("#template-modal .modal-body").hide();$("#template-modal-repo").show()});$(".template-modal-show-depot").on("click",function(event){event.preventDefault();activateTemplateNav($(this));$("#template-modal .modal-body").hide();$("#template-modal-depot").show()});$(".include-new-use").on("click",function(){var templateName=getIncludeTemplateName($(this));var useInclude=createNewUseInclude(templateName);$("#primary-include-list").append(useInclude);$("#template-modal").modal("hide");saveCodeEditor(getCodeEditorParams(),$("#save-template-form button"))});$(".include-new-extends").on("click",function(){var templateName=getIncludeTemplateName($(this));var oldName=getExtends();var extendsInclude=createNewExtendsInclude(templateName);var takeAction=false;if(!oldName){takeAction=true}else if(oldName===templateName){$("#template-modal").modal("hide")}else if(oldName&&confirm("Are you sure want want to extend "+templateName+" in place of "+oldName+" ?")){takeAction=true}if(takeAction){$("#primary-extends-list").empty().html(extendsInclude);$("#template-modal").modal("hide");saveCodeEditor(getCodeEditorParams(),$("#save-template-form button"))}});function getIncludeTemplateName($this){return $this.parents("tr").attr("data-template-name")}function activateTemplateNav($this){$(".template-modal-nav").removeClass("template-modal-active-anchor");$this.addClass("template-modal-active-anchor")}function createNewUseInclude(name){return'<li class="included-item" data-include-type="use" data-template-name="'+name+'"><a href="#">'+name+"</a></li>"}function createNewExtendsInclude(name){return'<li class="included-item" data-include-type="extends" data-template-name="'+name+'"><a href="#">'+name+"</a></li>"}function getExtends(){return $("#primary-extends-list").find(".included-item").attr("data-template-name")}function getUses(){var arr=[];$("#primary-include-list .included-item").each(function(){arr.push($(this).attr("data-template-name"))});return arr}