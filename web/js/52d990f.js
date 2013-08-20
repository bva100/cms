!function($){var Radio=function(element,options){this.init(element,options)};Radio.prototype={constructor:Radio,init:function(element,options){var $el=this.$element=$(element);this.options=$.extend({},$.fn.radio.defaults,options);$el.before(this.options.template);this.setState()},setState:function(){var $el=this.$element,$parent=$el.closest(".radio");$el.prop("disabled")&&$parent.addClass("disabled");$el.prop("checked")&&$parent.addClass("checked")},toggle:function(){var d="disabled",ch="checked",$el=this.$element,checked=$el.prop(ch),$parent=$el.closest(".radio"),$parentWrap=$el.closest("form").length?$el.closest("form"):$el.closest("body"),$elemGroup=$parentWrap.find(':radio[name="'+$el.attr("name")+'"]'),e=$.Event("toggle");$elemGroup.not($el).each(function(){var $el=$(this),$parent=$(this).closest(".radio");if($el.prop(d)==false){$parent.removeClass(ch)&&$el.attr(ch,false).trigger("change")}});if($el.prop(d)==false){if(checked==false)$parent.addClass(ch)&&$el.attr(ch,true);$el.trigger(e);if(checked!==$el.prop(ch)){$el.trigger("change")}}},setCheck:function(option){var ch="checked",$el=this.$element,$parent=$el.closest(".radio"),checkAction=option=="check"?true:false,checked=$el.prop(ch),$parentWrap=$el.closest("form").length?$el.closest("form"):$el.closest("body"),$elemGroup=$parentWrap.find(':radio[name="'+$el["attr"]("name")+'"]'),e=$.Event(option);$elemGroup.not($el).each(function(){var $el=$(this),$parent=$(this).closest(".radio");$parent.removeClass(ch)&&$el.removeAttr(ch)});$parent[checkAction?"addClass":"removeClass"](ch)&&checkAction?$el.attr(ch,true):$el.removeAttr(ch);$el.trigger(e);if(checked!==$el.prop(ch)){$el.trigger("change")}}};var old=$.fn.radio;$.fn.radio=function(option){return this.each(function(){var $this=$(this),data=$this.data("radio"),options=$.extend({},$.fn.radio.defaults,$this.data(),typeof option=="object"&&option);if(!data)$this.data("radio",data=new Radio(this,options));if(option=="toggle")data.toggle();if(option=="check"||option=="uncheck")data.setCheck(option);else if(option)data.setState()})};$.fn.radio.defaults={template:'<span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span>'};$.fn.radio.noConflict=function(){$.fn.radio=old;return this};$(document).on("click.radio.data-api","[data-toggle^=radio], .radio",function(e){var $radio=$(e.target);if(e.target.tagName!="A"){e&&e.preventDefault()&&e.stopPropagation();if(!$radio.hasClass("radio"))$radio=$radio.closest(".radio");$radio.find(":radio").radio("toggle")}});$(window).on("load",function(){$('[data-toggle="radio"]').each(function(){var $radio=$(this);$radio.radio()})})}(window.jQuery);
var DOMeditor=document.getElementById("input-code-content");var codeEditor=ace.edit("input-code-content");$(document).ready(function(){codeEditor.getSession().setMode("ace/mode/javascript");codeEditor.setTheme("ace/theme/textmate");codeEditor.getSession().setUseSoftTabs(true);codeEditor.getSession().setTabSize(2);DOMeditor.style.fontSize="14px";DOMeditor.style.lineHeight="1.4";DOMeditor.style.fontFamily="DejaVu Sans Mono";codeEditor.commands.addCommand({name:"saveFile",bindKey:{win:"Ctrl-S",mac:"Command-S",sender:"editor|cli"},exec:function(env,args,request){var $button=$(".save-code");saveCodeEditor(getCodeEditorParams(),$button)}})});function setAssetEditorMode(mode){switch(mode){case"javascript":codeEditor.getSession().setMode("ace/mode/javascript");break;case"css":codeEditor.getSession().setMode("ace/mode/css");break;default:break}return 1}function getCodeEditorContent(){return codeEditor.getValue()}
$(document).ready(function(){setAssetType(getAssetType())});$("#label-radio-ext-css").on("click",function(){setAssetType("css");$("h1").text("Create a Stylesheet Asset");$("#asset-type").hide()});$("#label-radio-ext-js").on("click",function(){setAssetType("js");$("h1").text("Create a Javascript Asset");$("#asset-type").hide()});$("#asset-form").on("submit",function(event){event.preventDefault();saveCodeEditor(getCodeEditorParams(),$(this).find("button"))});function setAssetType(type){if(type==="css"){document.getElementById("input-ext").value="css";setAssetEditorMode("css")}else if(type==="js"){document.getElementById("input-ext").value="js";setAssetEditorMode("javascript")}return 1}function getAssetType(){var assetTypeId=$("#asset-type input:checked").attr("id");if(assetTypeId==="radio-ext-css"){return"css"}else if(assetTypeId==="radio-ex-js"){return"js"}return""}function saveCodeEditor(params,$button){var saveCodePath=document.getElementById("save-code-path").value;$button.text("Saving...").attr("disabled",true);$.post(saveCodePath,params,function(data,textStatus,xhr){if(xhr.status===200){$("#error-container").html('<div class="alert alert-info"><i class="icon-bullhorn"></i> Saved</div>');$button.attr("disabled",false).removeClass("btn-info").addClass("btn-primary").text("Saved").delay(1100).queue(function(){$button.removeClass("btn-primary").addClass("btn-info").text("Save");$(this).dequeue()})}else{$("#error-container").html('<div class="alert alert-danger"><i class="icon-warning-sign"></i> Failed</div>');$button.attr("disabled",false).removeClass("btn-info").addClass("btn-primary").text("Saved").delay(1100).queue(function(){$button.removeClass("btn-primary").addClass("btn-info").text("Save");$(this).dequeue()})}})}function getCodeEditorParams(){var params={};params.id=document.getElementById("input-id").value;params.siteId=document.getElementById("input-site-id").value;params.ext=document.getElementById("input-ext").value;params.name=document.getElementById("input-name").value;params.content=getCodeEditorContent();return params}