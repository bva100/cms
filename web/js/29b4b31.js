!function(b){var c=function(e,d){this.init(e,d)};c.prototype={constructor:c,init:function(f,d){var e=this.$element=b(f);this.options=b.extend({},b.fn.radio.defaults,d);e.before(this.options.template);this.setState()},setState:function(){var d=this.$element,e=d.closest(".radio");d.prop("disabled")&&e.addClass("disabled");d.prop("checked")&&e.addClass("checked")},toggle:function(){var m="disabled",h="checked",g=this.$element,i=g.prop(h),k=g.closest(".radio"),l=g.closest("form").length?g.closest("form"):g.closest("body"),f=l.find(':radio[name="'+g.attr("name")+'"]'),j=b.Event("toggle");f.not(g).each(function(){var d=b(this),e=b(this).closest(".radio");if(d.prop(m)==false){e.removeClass(h)&&d.attr(h,false).trigger("change")}});if(g.prop(m)==false){if(i==false){k.addClass(h)&&g.attr(h,true)}g.trigger(j);if(i!==g.prop(h)){g.trigger("change")}}},setCheck:function(h){var d="checked",m=this.$element,g=m.closest(".radio"),f=h=="check"?true:false,j=m.prop(d),k=m.closest("form").length?m.closest("form"):m.closest("body"),l=k.find(':radio[name="'+m.attr("name")+'"]'),i=b.Event(h);l.not(m).each(function(){var e=b(this),n=b(this).closest(".radio");n.removeClass(d)&&e.removeAttr(d)});g[f?"addClass":"removeClass"](d)&&f?m.attr(d,true):m.removeAttr(d);m.trigger(i);if(j!==m.prop(d)){m.trigger("change")}}};var a=b.fn.radio;b.fn.radio=function(d){return this.each(function(){var g=b(this),f=g.data("radio"),e=b.extend({},b.fn.radio.defaults,g.data(),typeof d=="object"&&d);if(!f){g.data("radio",(f=new c(this,e)))}if(d=="toggle"){f.toggle()}if(d=="check"||d=="uncheck"){f.setCheck(d)}else{if(d){f.setState()}}})};b.fn.radio.defaults={template:'<span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span>'};b.fn.radio.noConflict=function(){b.fn.radio=a;return this};b(document).on("click.radio.data-api","[data-toggle^=radio], .radio",function(f){var d=b(f.target);if(f.target.tagName!="A"){f&&f.preventDefault()&&f.stopPropagation();if(!d.hasClass("radio")){d=d.closest(".radio")}d.find(":radio").radio("toggle")}});b(window).on("load",function(){b('[data-toggle="radio"]').each(function(){var d=b(this);d.radio()})})}(window.jQuery);
!function(b){var a=function(d,c,f){if(f){f.stopPropagation();f.preventDefault()}this.$element=b(d);this.$newElement=null;this.button=null;this.options=b.extend({},b.fn.selectpicker.defaults,this.$element.data(),typeof c=="object"&&c);if(this.options.title==null){this.options.title=this.$element.attr("title")}this.val=a.prototype.val;this.render=a.prototype.render;this.init()};a.prototype={constructor:a,init:function(u){var s=this;this.$element.hide();this.multiple=this.$element.prop("multiple");var w=this.$element.attr("class")!==undefined?this.$element.attr("class").split(/\s+/):"";var p=this.$element.attr("id");this.$element.after(this.createView());this.$newElement=this.$element.next(".select");var q=this.$newElement;var d=this.$newElement.find(".dropdown-menu");var j=this.$newElement.find(".dropdown-arrow");var n=d.find("li > a");var g=q.addClass("open").find(".dropdown-menu li > a").outerHeight();q.removeClass("open");var h=d.find("li .divider").outerHeight(true);var k=this.$newElement.offset().top;var m=0;var o=0;var v=this.$newElement.outerHeight();this.button=this.$newElement.find("> button");if(p!==undefined){this.button.attr("id",p);b('label[for="'+p+'"]').click(function(){q.find("button#"+p).focus()})}for(var t=0;t<w.length;t++){if(w[t]!="selectpicker"){this.$newElement.addClass(w[t])}}if(this.multiple){this.$newElement.addClass("select-multiple")}this.button.addClass(this.options.style);d.addClass(this.options.menuStyle);j.addClass(function(){if(s.options.menuStyle){return s.options.menuStyle.replace("dropdown-","dropdown-arrow-")}});this.checkDisabled();this.checkTabIndex();this.clickListener();var r=parseInt(d.css("padding-top"))+parseInt(d.css("padding-bottom"))+parseInt(d.css("border-top-width"))+parseInt(d.css("border-bottom-width"));if(this.options.size=="auto"){function f(){var e=k-b(window).scrollTop();var y=window.innerHeight;var i=r+parseInt(d.css("margin-top"))+parseInt(d.css("margin-bottom"))+2;var x=y-e-v-i;o=x;if(q.hasClass("dropup")){o=e-i}d.css({"max-height":o+"px","overflow-y":"auto","min-height":g*3+"px"})}f();b(window).resize(f);b(window).scroll(f);this.$element.bind("DOMNodeInserted",f)}else{if(this.options.size&&this.options.size!="auto"&&d.find("li").length>this.options.size){var l=d.find("li > *").filter(":not(.divider)").slice(0,this.options.size).last().parent().index();var c=d.find("li").slice(0,l+1).find(".divider").length;o=g*this.options.size+c*h+r;d.css({"max-height":o+"px","overflow-y":"scroll"})}}this.$element.bind("DOMNodeInserted",b.proxy(this.reloadLi,this));this.render()},createDropdown:function(){var c="<div class='btn-group select'><i class='dropdown-arrow'></i><button class='btn dropdown-toggle clearfix' data-toggle='dropdown'><span class='filter-option pull-left'></span>&nbsp;<span class='caret'></span></button><ul class='dropdown-menu' role='menu'></ul></div>";return b(c)},createView:function(){var c=this.createDropdown();var d=this.createLi();c.find("ul").append(d);return c},reloadLi:function(){this.destroyLi();$li=this.createLi();this.$newElement.find("ul").append($li);this.render()},destroyLi:function(){this.$newElement.find("li").remove()},createLi:function(){var h=this;var e=[];var g=[];var c="";this.$element.find("option").each(function(){e.push(b(this).text())});this.$element.find("option").each(function(k){var n=b(this).attr("class")!==undefined?b(this).attr("class"):"";var m=b(this).text();var l=b(this).data("subtext")!==undefined?'<small class="muted">'+b(this).data("subtext")+"</small>":"";m+=l;if(b(this).parent().is("optgroup")&&b(this).data("divider")!=true){if(b(this).index()==0){var j=b(this).parent().attr("label");var i=b(this).parent().data("subtext")!==undefined?'<small class="muted">'+b(this).parent().data("subtext")+"</small>":"";j+=i;if(b(this)[0].index!=0){g.push('<div class="divider"></div><dt>'+j+"</dt>"+h.createA(m,"opt "+n))}else{g.push("<dt>"+j+"</dt>"+h.createA(m,"opt "+n))}}else{g.push(h.createA(m,"opt "+n))}}else{if(b(this).data("divider")==true){g.push('<div class="divider"></div>')}else{g.push(h.createA(m,n))}}});if(e.length>0){for(var d=0;d<e.length;d++){var f=this.$element.find("option").eq(d);c+="<li rel="+d+">"+g[d]+"</li>"}}if(this.$element.find("option:selected").length==0&&!h.options.title){this.$element.find("option").eq(0).prop("selected",true).attr("selected","selected")}return b(c)},createA:function(d,c){return'<a tabindex="-1" href="#" class="'+c+'"><span class="pull-left">'+d+"</span></a>"},render:function(){var g=this;if(this.options.width=="auto"){var d=this.$newElement.find(".dropdown-menu").css("width");this.$newElement.css("width",d)}else{if(this.options.width&&this.options.width!="auto"){this.$newElement.css("width",this.options.width)}}this.$element.find("option").each(function(h){g.setDisabled(h,b(this).is(":disabled")||b(this).parent().is(":disabled"));g.setSelected(h,b(this).is(":selected"))});var f=this.$element.find("option:selected").map(function(h,i){if(b(this).attr("title")!=undefined){return b(this).attr("title")}else{return b(this).text()}}).toArray();var e=f.join(", ");if(g.multiple&&g.options.selectedTextFormat.indexOf("count")>-1){var c=g.options.selectedTextFormat.split(">");if((c.length>1&&f.length>c[1])||(c.length==1&&f.length>=2)){e=f.length+" of "+this.$element.find("option").length+" selected"}}if(!e){e=g.options.title!=undefined?g.options.title:g.options.noneSelectedText}this.$element.next(".select").find(".filter-option").html(e)},setSelected:function(c,d){if(d){this.$newElement.find("li").eq(c).addClass("selected")}else{this.$newElement.find("li").eq(c).removeClass("selected")}},setDisabled:function(c,d){if(d){this.$newElement.find("li").eq(c).addClass("disabled")}else{this.$newElement.find("li").eq(c).removeClass("disabled")}},checkDisabled:function(){if(this.$element.is(":disabled")){this.button.addClass("disabled");this.button.click(function(c){c.preventDefault()})}},checkTabIndex:function(){if(this.$element.is("[tabindex]")){var c=this.$element.attr("tabindex");this.button.attr("tabindex",c)}},clickListener:function(){var c=this;b("body").on("touchstart.dropdown",".dropdown-menu",function(d){d.stopPropagation()});this.$newElement.on("click","li a",function(i){var g=b(this).parent().index(),h=b(this).parent(),d=h.parents(".select");if(c.multiple){i.stopPropagation()}i.preventDefault();if(d.prev("select").not(":disabled")&&!b(this).parent().hasClass("disabled")){if(!c.multiple){d.prev("select").find("option").removeAttr("selected");d.prev("select").find("option").eq(g).prop("selected",true).attr("selected","selected")}else{var f=d.prev("select").find("option").eq(g).prop("selected");if(f){d.prev("select").find("option").eq(g).removeAttr("selected")}else{d.prev("select").find("option").eq(g).prop("selected",true).attr("selected","selected")}}d.find(".filter-option").html(h.text());d.find("button").focus();d.prev("select").trigger("change")}});this.$newElement.on("click","li.disabled a, li dt, li .divider",function(d){d.preventDefault();d.stopPropagation();$select=b(this).parent().parents(".select");$select.find("button").focus()});this.$element.on("change",function(d){c.render()})},val:function(c){if(c!=undefined){this.$element.val(c);this.$element.trigger("change");return this.$element}else{return this.$element.val()}}};b.fn.selectpicker=function(e,f){var c=arguments;var g;var d=this.each(function(){var l=b(this),k=l.data("selectpicker"),h=typeof e=="object"&&e;if(!k){l.data("selectpicker",(k=new a(this,h,f)))}else{for(var j in e){k[j]=e[j]}}if(typeof e=="string"){property=e;if(k[property] instanceof Function){[].shift.apply(c);g=k[property].apply(k,c)}else{g=k[property]}}});if(g!=undefined){return g}else{return d}};b.fn.selectpicker.defaults={style:null,size:"auto",title:null,selectedTextFormat:"values",noneSelectedText:"Nothing selected",width:null,menuStyle:null,toggleSize:null}}(window.jQuery);
!function(b){var c=function(e,d){this.init(e,d)};c.prototype={constructor:c,init:function(f,d){var e=this.$element=b(f);this.options=b.extend({},b.fn.checkbox.defaults,d);e.before(this.options.template);this.setState()},setState:function(){var d=this.$element,e=d.closest(".checkbox");d.prop("disabled")&&e.addClass("disabled");d.prop("checked")&&e.addClass("checked")},toggle:function(){var f="checked",d=this.$element,i=d.closest(".checkbox"),g=d.prop(f),h=b.Event("toggle");if(d.prop("disabled")==false){i.toggleClass(f)&&g?d.removeAttr(f):d.attr(f,true);d.trigger(h).trigger("change")}},setCheck:function(i){var l="disabled",h="checked",g=this.$element,k=g.closest(".checkbox"),f=i=="check"?true:false,j=b.Event(i);k[f?"addClass":"removeClass"](h)&&f?g.attr(h,true):g.removeAttr(h);g.trigger(j).trigger("change")}};var a=b.fn.checkbox;b.fn.checkbox=function(d){return this.each(function(){var g=b(this),f=g.data("checkbox"),e=b.extend({},b.fn.checkbox.defaults,g.data(),typeof d=="object"&&d);if(!f){g.data("checkbox",(f=new c(this,e)))}if(d=="toggle"){f.toggle()}if(d=="check"||d=="uncheck"){f.setCheck(d)}else{if(d){f.setState()}}})};b.fn.checkbox.defaults={template:'<span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span>'};b.fn.checkbox.noConflict=function(){b.fn.checkbox=a;return this};b(document).on("click.checkbox.data-api","[data-toggle^=checkbox], .checkbox",function(f){var d=b(f.target);if(f.target.tagName!="A"){f&&f.preventDefault()&&f.stopPropagation();if(!d.hasClass("checkbox")){d=d.closest(".checkbox")}d.find(":checkbox").checkbox("toggle")}});b(window).on("load",function(){b('[data-toggle="checkbox"]').each(function(){var d=b(this);d.checkbox()})})}(window.jQuery);
$(document).ready(function(){$(".flatui-calendar-input-start").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(a){$("#end-date").datepicker("option","minDate",a);$("#end-date").datepicker("show")}});$(".flatui-calendar-input-end").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(a){$("#start-date").datepicker("option","maxDate",a)}});$(".flatui-calendar").datepicker({showOtherMonths:true,selectOtherMonths:true,});$(".flatui-calendar").on("click",function(){$("#flatui-calendar-input-container").toggle();$(this).blur()});$("#flatui-calendar-confirm-dates").on("click",function(){$(".flatui-calendar-text").text("Change Dates");$("#flatui-calendar-input-container").hide()});$("#flatui-calendar-cancel-dates").on("click",function(){$("#start-date").val("");$("#end-date").val("");$(".flatui-calendar-text").text("View All Dates");$("#flatui-calendar-input-container").hide()})});
(function(c){var a=new Array();var b=new Array();c.fn.doAutosize=function(h){var f=c(this).data("minwidth"),p=c(this).data("maxwidth"),k="",n=c(this),j=c("#"+c(this).data("tester_id"));if(k===(k=n.val())){return}var e=k.replace(/&/g,"&amp;").replace(/\s/g," ").replace(/</g,"&lt;").replace(/>/g,"&gt;");j.html(e);var m=j.width(),l=(m+h.comfortZone)>=f?m+h.comfortZone:f,g=n.width(),d=(l<g&&l>=f)||(l>f&&l<p);if(d){n.width(l)}};c.fn.resetAutosize=function(f){var h=c(this).data("minwidth")||f.minInputWidth||c(this).width(),j=c(this).data("maxwidth")||f.maxInputWidth||(c(this).closest(".tagsinput").width()-f.inputPadding),k="",e=c(this),g=c("<tester/>").css({position:"absolute",top:-9999,left:-9999,width:"auto",fontSize:e.css("fontSize"),fontFamily:e.css("fontFamily"),fontWeight:e.css("fontWeight"),letterSpacing:e.css("letterSpacing"),whiteSpace:"nowrap"}),d=c(this).attr("id")+"_autosize_tester";if(!c("#"+d).length>0){g.attr("id",d);g.appendTo("body")}e.data("minwidth",h);e.data("maxwidth",j);e.data("tester_id",d);e.css("width",h)};c.fn.addTag=function(e,d){d=jQuery.extend({focus:false,callback:true},d);this.each(function(){var l=c(this).attr("id");var g=c(this).val().split(a[l]);if(g[0]==""){g=new Array()}e=jQuery.trim(e);if(d.unique){var h=c(this).tagExist(e);if(h==true){c("#"+l+"_tag").addClass("not_valid")}}else{var h=false}if(e!=""&&h!=true){c("<span>").addClass("tag").append(c("<span>").text(e).append("&nbsp;&nbsp;"),c('<a class="tagsinput-remove-link">',{href:"#",title:"Remove tag",text:""}).click(function(){return c("#"+l).removeTag(escape(e))})).insertBefore("#"+l+"_addTag");g.push(e);c("#"+l+"_tag").val("");if(d.focus){c("#"+l+"_tag").focus()}else{c("#"+l+"_tag").blur()}c.fn.tagsInput.updateTagsField(this,g);if(d.callback&&b[l]&&b[l]["onAddTag"]){var k=b[l]["onAddTag"];k.call(this,e)}if(b[l]&&b[l]["onChange"]){var j=g.length;var k=b[l]["onChange"];k.call(this,c(this),g[j-1])}}});return false};c.fn.removeTag=function(d){d=unescape(d);this.each(function(){var h=c(this).attr("id");var e=c(this).val().split(a[h]);c("#"+h+"_tagsinput .tag").remove();str="";for(i=0;i<e.length;i++){if(e[i]!=d){str=str+a[h]+e[i]}}c.fn.tagsInput.importTags(this,str);if(b[h]&&b[h]["onRemoveTag"]){var g=b[h]["onRemoveTag"];g.call(this,d)}});return false};c.fn.tagExist=function(e){var f=c(this).attr("id");var d=c(this).val().split(a[f]);return(jQuery.inArray(e,d)>=0)};c.fn.importTags=function(d){id=c(this).attr("id");c("#"+id+"_tagsinput .tag").remove();c.fn.tagsInput.importTags(this,d)};c.fn.tagsInput=function(d){var e=jQuery.extend({interactive:true,defaultText:"",minChars:0,width:"",height:"",autocomplete:{selectFirst:false},hide:true,delimiter:",",unique:true,removeWithBackspace:true,placeholderColor:"#666666",autosize:true,comfortZone:20,inputPadding:6*2},d);this.each(function(){if(e.hide){c(this).hide()}var j=c(this).attr("id");if(!j||a[c(this).attr("id")]){j=c(this).attr("id","tags"+new Date().getTime()).attr("id")}var g=jQuery.extend({pid:j,real_input:"#"+j,holder:"#"+j+"_tagsinput",input_wrapper:"#"+j+"_addTag",fake_input:"#"+j+"_tag"},e);a[j]=g.delimiter;if(e.onAddTag||e.onRemoveTag||e.onChange){b[j]=new Array();b[j]["onAddTag"]=e.onAddTag;b[j]["onRemoveTag"]=e.onRemoveTag;b[j]["onChange"]=e.onChange}var h=c("#"+j).attr("class").replace("tagsinput","");var f='<div id="'+j+'_tagsinput" class="tagsinput '+h+'"><div class="tagsinput-add-container" id="'+j+'_addTag"><div class="tagsinput-add"></div>';if(e.interactive){f=f+'<input id="'+j+'_tag" value="" data-default="'+e.defaultText+'" />'}f=f+"</div></div>";c(f).insertAfter(this);c(g.holder).css("width",e.width);c(g.holder).css("min-height",e.height);c(g.holder).css("height","100%");if(c(g.real_input).val()!=""){c.fn.tagsInput.importTags(c(g.real_input),c(g.real_input).val())}if(e.interactive){c(g.fake_input).val(c(g.fake_input).attr("data-default"));c(g.fake_input).css("color",e.placeholderColor);c(g.fake_input).resetAutosize(e);c(g.holder).bind("click",g,function(k){c(k.data.fake_input).focus()});c(g.fake_input).bind("focus",g,function(k){if(c(k.data.fake_input).val()==c(k.data.fake_input).attr("data-default")){c(k.data.fake_input).val("")}c(k.data.fake_input).css("color","#000000")});if(e.autocomplete_url!=undefined){autocomplete_options={source:e.autocomplete_url};for(attrname in e.autocomplete){autocomplete_options[attrname]=e.autocomplete[attrname]}if(jQuery.Autocompleter!==undefined){c(g.fake_input).autocomplete(e.autocomplete_url,e.autocomplete);c(g.fake_input).bind("result",g,function(k,m,l){if(m){c("#"+j).addTag(m[0]+"",{focus:true,unique:(e.unique)})}})}else{if(jQuery.ui.autocomplete!==undefined){c(g.fake_input).autocomplete(autocomplete_options);c(g.fake_input).bind("autocompleteselect",g,function(k,l){c(k.data.real_input).addTag(l.item.value,{focus:true,unique:(e.unique)});return false})}}}else{c(g.fake_input).bind("blur",g,function(k){var l=c(this).attr("data-default");if(c(k.data.fake_input).val()!=""&&c(k.data.fake_input).val()!=l){if((k.data.minChars<=c(k.data.fake_input).val().length)&&(!k.data.maxChars||(k.data.maxChars>=c(k.data.fake_input).val().length))){c(k.data.real_input).addTag(c(k.data.fake_input).val(),{focus:true,unique:(e.unique)})}}else{c(k.data.fake_input).val(c(k.data.fake_input).attr("data-default"));c(k.data.fake_input).css("color",e.placeholderColor)}return false})}c(g.fake_input).bind("keypress",g,function(k){if(k.which==k.data.delimiter.charCodeAt(0)||k.which==13){k.preventDefault();if((k.data.minChars<=c(k.data.fake_input).val().length)&&(!k.data.maxChars||(k.data.maxChars>=c(k.data.fake_input).val().length))){c(k.data.real_input).addTag(c(k.data.fake_input).val(),{focus:true,unique:(e.unique)})}c(k.data.fake_input).resetAutosize(e);return false}else{if(k.data.autosize){c(k.data.fake_input).doAutosize(e)}}});g.removeWithBackspace&&c(g.fake_input).bind("keydown",function(l){if(l.keyCode==8&&c(this).val()==""){l.preventDefault();var k=c(this).closest(".tagsinput").find(".tag:last").text();var m=c(this).attr("id").replace(/_tag$/,"");k=k.replace(/[\s\u00a0]+x$/,"");c("#"+m).removeTag(escape(k));c(this).trigger("focus")}});c(g.fake_input).blur();if(g.unique){c(g.fake_input).keydown(function(k){if(k.keyCode==8||String.fromCharCode(k.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/)){c(this).removeClass("not_valid")}})}}});return this};c.fn.tagsInput.updateTagsField=function(e,d){var f=c(e).attr("id");c(e).val(d.join(a[f]))};c.fn.tagsInput.importTags=function(g,h){c(g).val("");var j=c(g).attr("id");var d=h.split(a[j]);for(i=0;i<d.length;i++){c(g).addTag(d[i],{focus:false,callback:false})}if(b[j]&&b[j]["onChange"]){var e=b[j]["onChange"];e.call(g,g,d[i])}}})(jQuery);
var filepickerKey="AMPdbi1aZQuuzMBLLznNWz";function convertInkToMediaParams(a,b,c){mediaParams={};mediaParams.filename=a.key;mediaParams.storage="S3";mediaParams.url=a.url;mediaParams.mime=a.mimetype;mediaParams.size=a.size;mediaParams.siteId=b;if(c){mediaParams.nodeId=c}return mediaParams};
var mediaReadAllPath=document.getElementById("media-read-all-path").value;var mediaSavePath=document.getElementById("media-save-path").value;var siteId=document.getElementById("site-id").value;var nodeId=document.getElementById("node-id").value;$(document).ready(function(){loadMediaData(getMediaModalParams())});$(".media-modal-opener").on("click",function(){showMediaModal()});$(".media-modal-filter").on("click",function(){loadMediaData(getMediaModalParams())});$("#form-media-modal-filter").on("submit",function(a){a.preventDefault();loadMediaData(getMediaModalParams())});$(".media-modal-pag-next").on("click",function(){var a=getMediaModalParams();a.page++;loadMediaData(a)});$(".media-modal-pag-previous").on("click",function(){var a=getMediaModalParams();a.page--;loadMediaData(a)});$(".insert-media").on("click",function(){var d=$(this).attr("data-editor");var c=getSelected();var f=c.title?c.title:"";var e=c.alt?c.alt:"";var b='<img src="'+c.url+'" alt="'+e+'" title="'+f+'"/>';if(d==="tinyMCE"){var a=tinyMCE.activeEditor;a.selection.setContent(b)}$("#media-modal-container").modal("hide")});function showMediaModal(){$("#media-modal-container").modal("show");loadMediaData(getMediaModalParams());return 1}function getMediaModalParams(){var b=document.getElementById("media-modal-input-search").value;var c=document.getElementById("media-modal-input-type").value;var d=document.getElementById("media-modal-input-page").value;var a={};a.limit=18;if(b){a.search=b}if(c){a.type=c}if(!d){d=1}a.page=d;return a}function getMediaReadAllPath(a){if(!a){return mediaReadAllPath}else{if(a==="json"){return mediaReadAllPath+"?format=json"}}}$(".media-load-upload").on("click",function(a){a.preventDefault();loadUploader()});$(".media-load-library").on("click",function(a){a.preventDefault();loadMediaData(getMediaModalParams())});function loadUploader(){$(".media-load-upload").css("color","#95A5A6");$(".media-load-library").css("color","#16A085");$("#media-library-container").hide();$("#media-uploader-container").show();uploadInline("media-upload-iframe")}function uploadInline(a){filepicker.pickAndStore({container:a},{location:"S3",access:"public"},function(c){var b=convertInkToMediaParams(c[0],siteId,nodeId?nodeId:null);$.post(mediaAddPath,b,function(f,j,i){if(j=="success"){var h=b.title?b.title:"";var g=b.alt?b.alt:"";var e='<img src="'+b.url+'" alt="'+g+'" title="'+h+'"/>';var d=tinyMCE.activeEditor;d.selection.setContent(e);$("#media-modal-container").modal("hide")}else{alert("Upload failed. Please try again")}})})}function loadMediaData(a){$("#primary-media-data-container").html("");$.get(getMediaReadAllPath("json"),a,function(d,h,g){if(h==="success"){showHideMediaModalPag(d.length,a.limit,a.page);$(".media-load-upload").css("color","#16A085");$(".media-load-library").css("color","#95A5A6");$("#media-uploader-container").hide();$("#media-library-container").show();var c=document.getElementById("media-modal-loader");var f=document.getElementById("primary-media-data-container");var e="";c.className+=" hide";for(var b=0;b<d.length;b++){e=document.createElement("div");e.innerHTML="<img src='"+d[b].url+"' class='span2 media-preview' style='padding: 10px' data-media-json='"+JSON.stringify(d[b])+"'/>";f.appendChild(e)}$(".media-preview").on("click",function(){var j=$(this).attr("data-media-json");var i=$.parseJSON(j);selectMedia($(this));displayEditor(i)});return 1}else{alert("We are not able to load your media at this time. Please be sure you are logged in and try again.");return 0}})}function showHideMediaModalPag(b,a,c){if(b<a){$(".media-modal-pag-next").addClass("disabled")}else{$(".media-modal-pag-next").removeClass("disabled")}if(c<2){$(".media-modal-pag-previous").addClass("disabled")}else{$(".media-modal-pag-previous").removeClass("disabled")}}function displayEditor(d){var b=document.getElementById("primary-media-editor");var a='<div class="span12">';if(d.metadata.title){a+="<h4> Edit "+d.metadata.title+"</h4>";var e=d.metadata.title}else{a+="<h4>Edit Media</h4>";var e=""}if(d.metadata.alt){var c=d.metadata.alt}else{var c=""}a+='</div></div><div class="row-fluid"><div class="span12" style="margin-bottom: 10px;"><img src="'+d.url+'"/></div></div>';a+='<div class="row-fluid"><div class="span12"><form id="edit-media-form"><input type="text" id="input-media-edit-title" class="span12" style="margin: 10px 0px; font-size: 12px;" value="'+e+'" placeholder="title"/><input type="text" id="input-media-edit-alt" class="span12" style="margin: 10px 0px; font-size: 12px;" value="'+c+'" placeholder="alt"/><button class="btn btn-info btn-block media-edit-save">Save</button></form></div></div>';b.innerHTML=a;$("#input-media-edit-title").focus();$("#edit-media-form").on("submit",function(f){f.preventDefault();var g=getNewMediaParams(d);$(".media-edit-save").text("saving...");$.post(mediaSavePath,g,function(h,j,i){if(j==="success"){$(".media-edit-save").text("Save")}else{alert("Unable to edit media at this time. Please try again");$(".media-edit-save").text("Save")}})});return 1}function getNewMediaParams(a){objParams={};objParams.id=a.id;if(!a.metadata){a.metadata={}}a.metadata.title=document.getElementById("input-media-edit-title").value;a.metadata.alt=document.getElementById("input-media-edit-title").value;objParams.metadata=JSON.stringify(a.metadata);return objParams}function selectMedia(a){$(".media-preview").removeClass("media-selected");a.addClass("media-selected");return 1}function getSelected(){var a=$("#primary-media-data-container").find(".media-selected:first");var b=a.attr("data-media-json");return $.parseJSON(b)};
var savePath=document.getElementById("save-path").value;var deletePath=document.getElementById("delete-path").value;var readContentTypePath=document.getElementById("read-content-type-path").value;var baseUrl=document.getElementById("base-url").value;var mediaAddPath=document.getElementById("media-add-path").value;$(document).ready(function(){$("select").selectpicker();$(".select .dropdown-toggle").addClass("btn-info");$(".dropdown-menu").addClass("dropdown-inverse");$(".dropdown-arrow").addClass("dropdown-arrow-inverse");$("#tags").tagsInput();var a=$(".alert").length>0;if(a){$("#inner-notice-container").show(0).delay(1000).fadeOut(1000)}tinyMCE.init({plugins:"link, table, save, fullscreen, charmap, code, paste, media, contextmenu",save_enablewhendirty:true,save_onsavecallback:function(){saveAJAX(getParams())},skin:"flat",visual:false,statusbar:true,menubar:"view, edit, insert, format, table",toolbar:"undo, redo | bold, italic, strikethrough | alignleft, aligncenter, alignright, justify | bullist, numlist  outdent, indent |  code | charmap, link, image, cms-media",schema:"html5",selector:"#view-html",image_advtab:false,setup:function(b){b.addButton("cms-media",{title:"Media",type:"button",icon:"image",style:"float: right;",onclick:function(){showMediaModal()},})},});filepicker.setKey(filepickerKey)});$(".upload-media").on("click",function(a){a.preventDefault();if($(this).hasClass("upload-featured")){upload("featured")}else{upload("standard")}});function insertMedia(a){var b=tinyMCE.activeEditor;b.selection.setContent(a)}function upload(a){filepicker.pickAndStore({},{location:"S3",access:"public"},function(c){var d=getParams();var b=convertInkToMediaParams(c[0],d.siteId,d.id);$.post(mediaAddPath,b,function(e,g,f){if(g=="success"){if(a=="featured"){$(".featured-image-container").html('<img src="'+b.url+'" class="span12" id="input-featured-image">')}else{alert("added"+b.url)}if(d.id){saveAJAX(getParams())}}else{alert("Upload failed. Please try again")}})})}$("#state-container-opener").on("click",function(){$("#state-container").toggle()});$("#publish-date-container-opener").on("click",function(){$("#publish-date-container").toggle()});$("#input-state").on("change",function(){$("#submit-input-state").show()});$("#submit-input-state").on("click",function(){save(getParams())});$("#add-category-opener").on("click",function(){$("#add-category-container").toggle()});$("#cancel-add-category").on("click",function(){clearAddCategory()});$(".edit-fields").on("click",function(a){a.preventDefault()});$(".field-key-value-pairs-container-toggler").on("click",function(a){a.preventDefault();$(".field-key-value-pairs-container").show();$(this).hide();toggleAddField()});$(".add-field-toggler").on("click",function(a){a.preventDefault();toggleAddField()});$(".edit-fields").on("click",function(a){a.preventDefault();if($(this).hasClass("btn-inverse")){$(this).removeClass("btn-inverse").addClass("btn-primary").html('<i class="icon-save"> Done</i>');$(".add-field-toggler").hide();$("#add-field-footer-btn-container .save-node").hide();$(".field-key-input").attr("type","text").parent("div").removeClass("span4").addClass("span10");$(".field-key").hide();$(".field-value").hide();$(".field-key-value-pairs").append('<button class="btn btn-danger remove-field" style="position: relative; left: 10px;"><i class="icon-trash"> Delete</i></button>');$(".remove-field").on("click",function(b){b.preventDefault();if(confirm("Are you sure you want to remove this field?")){var d=$(this).parent("div");var c={key:d.attr("data-field-key"),value:d.attr("data-field-value")};d.remove()}})}else{$(this).removeClass("btn-primary").addClass("btn-inverse").html('<i class="icon-edit"></i> Edit');$(".add-field-toggler").show();$("#add-field-footer-btn-container .save-node").show();$(".field-key-input").attr("type","hidden").parent("div").removeClass("span10").addClass("span4");$(".field-key").show();$(".field-value").show();$(".remove-field").remove()}});$(".field-key-input").on("blur",function(){$(this).next().text($(this).val())});$(".create-field-key").on("click",function(b){b.preventDefault();var a=document.getElementById("input-new-field-key");var c=createNewField(a.value);$(".field-key-value-pairs-inner").append(c);a.value="";$(".add-field").hide()});$(".confirm-add-category").on("click",function(){addCategory()});$("#add-category-form").on("submit",function(a){a.preventDefault();addCategory()});$(".category-toggle-icon").on("click",function(){var a=$(this).attr("data-parent");switchCategorySub(a);switchToggleIcon($(this))});$(".category-parent-name").on("click",function(){var a=$(this).next();switchCategorySub(a.attr("data-parent"));switchToggleIcon(a)});$(".save-node").on("click",function(){saveAJAX(getParams())});$(".preview-node").on("click",function(){var a=getParams();saveAndPreview(a)});$(".delete-node").on("click",function(){if(confirm("Are you sure you want to permanently delete this item?")){var a=document.getElementById("input-token").value;var b=document.getElementById("input-id").value;$.post(deletePath,{id:b,token:a},function(c,e,d){if(e==="success"){window.location.href=readContentTypePath}else{alert("Unable to delete. Please try again.")}})}});$(".publish-node").on("click",function(){params=getParams();params.state="active";saveAndPreview(params)});$(".category-toggle-icon").on("click",function(){var c=$(this).attr("data-parent").toLowerCase();var d=$(".category-data tr[data-parent='"+c+"']");var b="";var a=$("#table-category-clone tr[data-parent='"+c+"']").html();d.html(b)});function switchCategorySub(a){$(".parent-"+a).toggle()}function switchToggleIcon(b){var a=b.attr("data-state");if(a==="opened"){b.html('<i class="icon-chevron-sign-left"></i>');b.attr("data-state","closed")}else{b.html('<i class="icon-chevron-sign-down"></i>');b.attr("data-state","opened")}}function clearAddCategory(){$("#add-category-parent").val("");$("#add-category-sub").val("");$("#add-category-container").hide()}function getFullSlug(b){var a=b.slug;var c=b.slugPrefix;if(a.indexOf(c)!==-1){return b.slug}else{return b.slugPrefix+b.slug}}function getUrl(a){return"/"+getFullSlug(a)}function getParams(){objParams={};objParams.token=document.getElementById("input-token").value;objParams.id=document.getElementById("input-id").value;objParams.state=document.getElementById("input-state").value;objParams.siteId=document.getElementById("input-site-id").value;objParams.contentTypeName=document.getElementById("input-content-type-name").value;objParams.format=document.getElementById("input-format").value;objParams.templateName=document.getElementById("input-template-name").value;objParams.slugPrefix=document.getElementById("input-slug-prefix").value;objParams.domain=document.getElementById("domain").value;objParams.slug=document.getElementById("slug").value;objParams.title=document.getElementById("title").value;objParams.description=document.getElementById("input-description").value;objParams.viewHtml=tinyMCE.activeEditor.getContent();objParams.viewText=tinyMCE.activeEditor.getContent({format:"raw"});objParams.description=document.getElementById("input-description").value;objParams.authorId=document.getElementById("input-author-id").value;objParams.authorFirstName=document.getElementById("input-author-first-name").value;objParams.authorLastName=document.getElementById("input-author-last-name").value;objParams.authorImage=document.getElementById("input-author-image").value;objParams.featuredImage=$("#input-featured-image").attr("src");objParams.categoriesJSON=JSON.stringify(getCategories());objParams.tagsJSON=JSON.stringify(getTags());objParams.fieldsJSON=JSON.stringify(getFields());return objParams}function getCategories(){var b=[];var a={};$("#categories-holder .checked").each(function(){var c=$(this).attr("data-category").split("-");if(c.length>1){a={parent:c[0],sub:c[1]}}else{a={parent:c[0]}}b.push(a)});return b}function getRawCategories(){var a=[];$("#categories-holder .checked").each(function(){a.push($(this).attr("data-category"))});return a}function addCategory(){var a=document.getElementById("add-category-path").value;params=getParams();catParams={};catParams.siteId=params.siteId;catParams.id=document.getElementById("input-content-type-id").value;catParams.parent=document.getElementById("add-category-parent").value;catParams.sub=document.getElementById("add-category-sub").value;$.post(a,catParams,function(b,d,c){if(d==="success"){save(params,"#categories-holder")}else{alert("Unable to add new category. Please try again.")}})}function getTags(){return document.getElementById("tags").value.split(",")}function createNewField(a){return'<div class="row-fluid field-key-value-pairs"><div class="span4"><input type="hidden" class="field-key-input" value="'+a+'"/><span class="field-key">'+a+'</span></div><div class="span8"><input type="text" class="span12 field-value" placeholder="add a value for '+a+'"/></div></div>'}function getFields(){var a=[];var b={};$(".field-key-value-pairs").each(function(){b.key=$(this).find(".field-key").text();b.value=$(this).find(".field-value").val();a.push(b);b={}});return a}function toggleAddField(){var a=$(".add-field");a.toggle();$("#input-new-field-key").focus()}function saveAJAX(a){$.post(savePath,a,function(b,d,c){if(d==="success"){$("#notice-container").html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" style="color: white">&times;</button><i class="icon-bullhorn" style="margin-right: 5px;"></i> Save Complete</div>')}else{alert("Unable to save. Please be sure you are logged in and try again. If problem persists please contact customer services.");return 0}})}function save(b,a){if(!a){a=""}$.post(savePath,b,function(c,e,d){if(e==="success"){window.location.href=savePath+"/"+c+a}else{alert("Unable to save. Please be sure you are logged in and try again. If problem persists please contact customer services.")}})}function saveAndPreview(b){if(!b.slug){alert("You must include a title before seeing a preview");return 0}var a=getUrl(b);$.post(savePath,b,function(c,e,d){if(e==="success"){window.location.href=savePath+"/"+c;window.open(a,"_blank")}else{alert("Unable to display preview. Please try again.");return 0}})};
var oldTitle=document.getElementById("title").value;$("#domain-container").on("click",function(){$("#slug-selector-container").hide();$("#domain-selector-container").toggle()});$(".radio-domain").click(function(){var a=$(this).find("input").attr("data-domain");updateDomain(a);$("#domain-selector-container").hide(400)});$(".radio-slug").on("click",function(){var b=$(this).attr("data-slug-type");var a=$("#title");if(b==="title"){a.attr("data-slug-title","on");var c=a.val();$("#slug-selector-container").hide()}else{a.attr("data-slug-title","off");$("#input-custom-slug-container").show();$("#button-custom-slug").show();var c=$("#input-custom-slug").val()}updateSlug(c)});$("#slug-container").on("click",function(){$("#domain-selector-container").hide();$("#slug-selector-container").toggle()});$("#title").blur(function(){if($(this).attr("data-slug-title")==="on"&&document.getElementById("title").value!=oldTitle){if(document.getElementById("state").value=="active"){oldTitle=($(this).val());if(!confirm("Would you like to update the url to match this new title?")){return 0}}updateSlug($(this).val())}});$("#input-custom-slug").blur(function(){var a=$(this).val();updateSlug(a);$("#slug-selector-container").hide()});function updateSlug(a){a=a.replace(/\s+/g,"-").toLowerCase();$("#slug-container").text(a);$("#slug").val(a)}function updateDomain(a){$("#domain-container").text(a);$("#domain").val(a)};