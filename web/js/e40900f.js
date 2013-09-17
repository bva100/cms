!function($){var Selectpicker=function(element,options,e){if(e){e.stopPropagation();e.preventDefault()}this.$element=$(element);this.$newElement=null;this.button=null;this.options=$.extend({},$.fn.selectpicker.defaults,this.$element.data(),typeof options=="object"&&options);if(this.options.title==null)this.options.title=this.$element.attr("title");this.val=Selectpicker.prototype.val;this.render=Selectpicker.prototype.render;this.init()};Selectpicker.prototype={constructor:Selectpicker,init:function(e){var _this=this;this.$element.hide();this.multiple=this.$element.prop("multiple");var classList=this.$element.attr("class")!==undefined?this.$element.attr("class").split(/\s+/):"";var id=this.$element.attr("id");this.$element.after(this.createView());this.$newElement=this.$element.next(".select");var select=this.$newElement;var menu=this.$newElement.find(".dropdown-menu");var menuArrow=this.$newElement.find(".dropdown-arrow");var menuA=menu.find("li > a");var liHeight=select.addClass("open").find(".dropdown-menu li > a").outerHeight();select.removeClass("open");var divHeight=menu.find("li .divider").outerHeight(true);var selectOffset_top=this.$newElement.offset().top;var size=0;var menuHeight=0;var selectHeight=this.$newElement.outerHeight();this.button=this.$newElement.find("> button");if(id!==undefined){this.button.attr("id",id);$('label[for="'+id+'"]').click(function(){select.find("button#"+id).focus()})}for(var i=0;i<classList.length;i++){if(classList[i]!="selectpicker"){this.$newElement.addClass(classList[i])}}if(this.multiple){this.$newElement.addClass("select-multiple")}this.button.addClass(this.options.style);menu.addClass(this.options.menuStyle);menuArrow.addClass(function(){if(_this.options.menuStyle){return _this.options.menuStyle.replace("dropdown-","dropdown-arrow-")}});this.checkDisabled();this.checkTabIndex();this.clickListener();var menuPadding=parseInt(menu.css("padding-top"))+parseInt(menu.css("padding-bottom"))+parseInt(menu.css("border-top-width"))+parseInt(menu.css("border-bottom-width"));if(this.options.size=="auto"){function getSize(){var selectOffset_top_scroll=selectOffset_top-$(window).scrollTop();var windowHeight=window.innerHeight;var menuExtras=menuPadding+parseInt(menu.css("margin-top"))+parseInt(menu.css("margin-bottom"))+2;var selectOffset_bot=windowHeight-selectOffset_top_scroll-selectHeight-menuExtras;menuHeight=selectOffset_bot;if(select.hasClass("dropup")){menuHeight=selectOffset_top_scroll-menuExtras}menu.css({"max-height":menuHeight+"px","overflow-y":"auto","min-height":liHeight*3+"px"})}getSize();$(window).resize(getSize);$(window).scroll(getSize);this.$element.bind("DOMNodeInserted",getSize)}else if(this.options.size&&this.options.size!="auto"&&menu.find("li").length>this.options.size){var optIndex=menu.find("li > *").filter(":not(.divider)").slice(0,this.options.size).last().parent().index();var divLength=menu.find("li").slice(0,optIndex+1).find(".divider").length;menuHeight=liHeight*this.options.size+divLength*divHeight+menuPadding;menu.css({"max-height":menuHeight+"px","overflow-y":"scroll"})}this.$element.bind("DOMNodeInserted",$.proxy(this.reloadLi,this));this.render()},createDropdown:function(){var drop="<div class='btn-group select'>"+"<i class='dropdown-arrow'></i>"+"<button class='btn dropdown-toggle clearfix' data-toggle='dropdown'>"+"<span class='filter-option pull-left'></span>&nbsp;"+"<span class='caret'></span>"+"</button>"+"<ul class='dropdown-menu' role='menu'>"+"</ul>"+"</div>";return $(drop)},createView:function(){var $drop=this.createDropdown();var $li=this.createLi();$drop.find("ul").append($li);return $drop},reloadLi:function(){this.destroyLi();$li=this.createLi();this.$newElement.find("ul").append($li);this.render()},destroyLi:function(){this.$newElement.find("li").remove()},createLi:function(){var _this=this;var _li=[];var _liA=[];var _liHtml="";this.$element.find("option").each(function(){_li.push($(this).text())});this.$element.find("option").each(function(index){var optionClass=$(this).attr("class")!==undefined?$(this).attr("class"):"";var text=$(this).text();var subtext=$(this).data("subtext")!==undefined?'<small class="muted">'+$(this).data("subtext")+"</small>":"";text+=subtext;if($(this).parent().is("optgroup")&&$(this).data("divider")!=true){if($(this).index()==0){var label=$(this).parent().attr("label");var labelSubtext=$(this).parent().data("subtext")!==undefined?'<small class="muted">'+$(this).parent().data("subtext")+"</small>":"";label+=labelSubtext;if($(this)[0].index!=0){_liA.push('<div class="divider"></div>'+"<dt>"+label+"</dt>"+_this.createA(text,"opt "+optionClass))}else{_liA.push("<dt>"+label+"</dt>"+_this.createA(text,"opt "+optionClass))}}else{_liA.push(_this.createA(text,"opt "+optionClass))}}else if($(this).data("divider")==true){_liA.push('<div class="divider"></div>')}else{_liA.push(_this.createA(text,optionClass))}});if(_li.length>0){for(var i=0;i<_li.length;i++){var $option=this.$element.find("option").eq(i);_liHtml+="<li rel="+i+">"+_liA[i]+"</li>"}}if(this.$element.find("option:selected").length==0&&!_this.options.title){this.$element.find("option").eq(0).prop("selected",true).attr("selected","selected")}return $(_liHtml)},createA:function(test,classes){return'<a tabindex="-1" href="#" class="'+classes+'">'+'<span class="pull-left">'+test+"</span>"+"</a>"},render:function(){var _this=this;if(this.options.width=="auto"){var ulWidth=this.$newElement.find(".dropdown-menu").css("width");this.$newElement.css("width",ulWidth)}else if(this.options.width&&this.options.width!="auto"){this.$newElement.css("width",this.options.width)}this.$element.find("option").each(function(index){_this.setDisabled(index,$(this).is(":disabled")||$(this).parent().is(":disabled"));_this.setSelected(index,$(this).is(":selected"))});var selectedItems=this.$element.find("option:selected").map(function(index,value){if($(this).attr("title")!=undefined){return $(this).attr("title")}else{return $(this).text()}}).toArray();var title=selectedItems.join(", ");if(_this.multiple&&_this.options.selectedTextFormat.indexOf("count")>-1){var max=_this.options.selectedTextFormat.split(">");if(max.length>1&&selectedItems.length>max[1]||max.length==1&&selectedItems.length>=2){title=selectedItems.length+" of "+this.$element.find("option").length+" selected"}}if(!title){title=_this.options.title!=undefined?_this.options.title:_this.options.noneSelectedText}this.$element.next(".select").find(".filter-option").html(title)},setSelected:function(index,selected){if(selected){this.$newElement.find("li").eq(index).addClass("selected")}else{this.$newElement.find("li").eq(index).removeClass("selected")}},setDisabled:function(index,disabled){if(disabled){this.$newElement.find("li").eq(index).addClass("disabled")}else{this.$newElement.find("li").eq(index).removeClass("disabled")}},checkDisabled:function(){if(this.$element.is(":disabled")){this.button.addClass("disabled");this.button.click(function(e){e.preventDefault()})}},checkTabIndex:function(){if(this.$element.is("[tabindex]")){var tabindex=this.$element.attr("tabindex");this.button.attr("tabindex",tabindex)}},clickListener:function(){var _this=this;$("body").on("touchstart.dropdown",".dropdown-menu",function(e){e.stopPropagation()});this.$newElement.on("click","li a",function(e){var clickedIndex=$(this).parent().index(),$this=$(this).parent(),$select=$this.parents(".select");if(_this.multiple){e.stopPropagation()}e.preventDefault();if($select.prev("select").not(":disabled")&&!$(this).parent().hasClass("disabled")){if(!_this.multiple){$select.prev("select").find("option").removeAttr("selected");$select.prev("select").find("option").eq(clickedIndex).prop("selected",true).attr("selected","selected")}else{var selected=$select.prev("select").find("option").eq(clickedIndex).prop("selected");if(selected){$select.prev("select").find("option").eq(clickedIndex).removeAttr("selected")}else{$select.prev("select").find("option").eq(clickedIndex).prop("selected",true).attr("selected","selected")}}$select.find(".filter-option").html($this.text());$select.find("button").focus();$select.prev("select").trigger("change")}});this.$newElement.on("click","li.disabled a, li dt, li .divider",function(e){e.preventDefault();e.stopPropagation();$select=$(this).parent().parents(".select");$select.find("button").focus()});this.$element.on("change",function(e){_this.render()})},val:function(value){if(value!=undefined){this.$element.val(value);this.$element.trigger("change");return this.$element}else{return this.$element.val()}}};$.fn.selectpicker=function(option,event){var args=arguments;var value;var chain=this.each(function(){var $this=$(this),data=$this.data("selectpicker"),options=typeof option=="object"&&option;if(!data){$this.data("selectpicker",data=new Selectpicker(this,options,event))}else{for(var i in option){data[i]=option[i]}}if(typeof option=="string"){property=option;if(data[property]instanceof Function){[].shift.apply(args);value=data[property].apply(data,args)}else{value=data[property]}}});if(value!=undefined){return value}else{return chain}};$.fn.selectpicker.defaults={style:null,size:"auto",title:null,selectedTextFormat:"values",noneSelectedText:"Nothing selected",width:null,menuStyle:null,toggleSize:null}}(window.jQuery);
!function($){var Checkbox=function(element,options){this.init(element,options)};Checkbox.prototype={constructor:Checkbox,init:function(element,options){var $el=this.$element=$(element);this.options=$.extend({},$.fn.checkbox.defaults,options);$el.before(this.options.template);this.setState()},setState:function(){var $el=this.$element,$parent=$el.closest(".checkbox");$el.prop("disabled")&&$parent.addClass("disabled");$el.prop("checked")&&$parent.addClass("checked")},toggle:function(){var ch="checked",$el=this.$element,$parent=$el.closest(".checkbox"),checked=$el.prop(ch),e=$.Event("toggle");if($el.prop("disabled")==false){$parent.toggleClass(ch)&&checked?$el.removeAttr(ch):$el.attr(ch,true);$el.trigger(e).trigger("change")}},setCheck:function(option){var d="disabled",ch="checked",$el=this.$element,$parent=$el.closest(".checkbox"),checkAction=option=="check"?true:false,e=$.Event(option);$parent[checkAction?"addClass":"removeClass"](ch)&&checkAction?$el.attr(ch,true):$el.removeAttr(ch);$el.trigger(e).trigger("change")}};var old=$.fn.checkbox;$.fn.checkbox=function(option){return this.each(function(){var $this=$(this),data=$this.data("checkbox"),options=$.extend({},$.fn.checkbox.defaults,$this.data(),typeof option=="object"&&option);if(!data)$this.data("checkbox",data=new Checkbox(this,options));if(option=="toggle")data.toggle();if(option=="check"||option=="uncheck")data.setCheck(option);else if(option)data.setState()})};$.fn.checkbox.defaults={template:'<span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span>'};$.fn.checkbox.noConflict=function(){$.fn.checkbox=old;return this};$(document).on("click.checkbox.data-api","[data-toggle^=checkbox], .checkbox",function(e){var $checkbox=$(e.target);if(e.target.tagName!="A"){e&&e.preventDefault()&&e.stopPropagation();if(!$checkbox.hasClass("checkbox"))$checkbox=$checkbox.closest(".checkbox");$checkbox.find(":checkbox").checkbox("toggle")}});$(window).on("load",function(){$('[data-toggle="checkbox"]').each(function(){var $checkbox=$(this);$checkbox.checkbox()})})}(window.jQuery);
$(document).ready(function(){$(".flatui-calendar-input-start").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(selectedDate){$("#end-date").datepicker("option","minDate",selectedDate);$("#end-date").datepicker("show")}});$(".flatui-calendar-input-end").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(selectedDate){$("#start-date").datepicker("option","maxDate",selectedDate)}});$(".flatui-calendar").datepicker({showOtherMonths:true,selectOtherMonths:true});$(".flatui-calendar").on("click",function(){$("#flatui-calendar-input-container").toggle();$(this).blur()});$("#flatui-calendar-confirm-dates").on("click",function(){$(".flatui-calendar-text").text("Change Dates");$("#flatui-calendar-input-container").hide()});$("#flatui-calendar-cancel-dates").on("click",function(){$("#start-date").val("");$("#end-date").val("");$(".flatui-calendar-text").text("View All Dates");$("#flatui-calendar-input-container").hide()})});
!function($){var delimiter=new Array;var tags_callbacks=new Array;$.fn.doAutosize=function(o){var minWidth=$(this).data("minwidth"),maxWidth=$(this).data("maxwidth"),val="",input=$(this),testSubject=$("#"+$(this).data("tester_id"));if(val===(val=input.val())){return}var escaped=val.replace(/&/g,"&amp;").replace(/\s/g," ").replace(/</g,"&lt;").replace(/>/g,"&gt;");testSubject.html(escaped);var testerWidth=testSubject.width(),newWidth=testerWidth+o.comfortZone>=minWidth?testerWidth+o.comfortZone:minWidth,currentWidth=input.width(),isValidWidthChange=newWidth<currentWidth&&newWidth>=minWidth||newWidth>minWidth&&newWidth<maxWidth;if(isValidWidthChange){input.width(newWidth)}};$.fn.resetAutosize=function(options){var minWidth=$(this).data("minwidth")||options.minInputWidth||$(this).width(),maxWidth=$(this).data("maxwidth")||options.maxInputWidth||$(this).closest(".tagsinput").width()-options.inputPadding,val="",input=$(this),testSubject=$("<tester/>").css({position:"absolute",top:-9999,left:-9999,width:"auto",fontSize:input.css("fontSize"),fontFamily:input.css("fontFamily"),fontWeight:input.css("fontWeight"),letterSpacing:input.css("letterSpacing"),whiteSpace:"nowrap"}),testerId=$(this).attr("id")+"_autosize_tester";if(!$("#"+testerId).length>0){testSubject.attr("id",testerId);testSubject.appendTo("body")}input.data("minwidth",minWidth);input.data("maxwidth",maxWidth);input.data("tester_id",testerId);input.css("width",minWidth)};$.fn.addTag=function(value,options){options=jQuery.extend({focus:false,callback:true},options);this.each(function(){var id=$(this).attr("id");var tagslist=$(this).val().split(delimiter[id]);if(tagslist[0]==""){tagslist=new Array}value=jQuery.trim(value);if(options.unique){var skipTag=$(this).tagExist(value);if(skipTag==true){$("#"+id+"_tag").addClass("not_valid")}}else{var skipTag=false}if(value!=""&&skipTag!=true){$("<span>").addClass("tag").append($("<span>").text(value).append("&nbsp;&nbsp;"),$('<a class="tagsinput-remove-link">',{href:"#",title:"Remove tag",text:""}).click(function(){return $("#"+id).removeTag(escape(value))})).insertBefore("#"+id+"_addTag");tagslist.push(value);$("#"+id+"_tag").val("");if(options.focus){$("#"+id+"_tag").focus()}else{$("#"+id+"_tag").blur()}$.fn.tagsInput.updateTagsField(this,tagslist);if(options.callback&&tags_callbacks[id]&&tags_callbacks[id]["onAddTag"]){var f=tags_callbacks[id]["onAddTag"];f.call(this,value)}if(tags_callbacks[id]&&tags_callbacks[id]["onChange"]){var i=tagslist.length;var f=tags_callbacks[id]["onChange"];f.call(this,$(this),tagslist[i-1])}}});return false};$.fn.removeTag=function(value){value=unescape(value);this.each(function(){var id=$(this).attr("id");var old=$(this).val().split(delimiter[id]);$("#"+id+"_tagsinput .tag").remove();str="";for(i=0;i<old.length;i++){if(old[i]!=value){str=str+delimiter[id]+old[i]}}$.fn.tagsInput.importTags(this,str);if(tags_callbacks[id]&&tags_callbacks[id]["onRemoveTag"]){var f=tags_callbacks[id]["onRemoveTag"];f.call(this,value)}});return false};$.fn.tagExist=function(val){var id=$(this).attr("id");var tagslist=$(this).val().split(delimiter[id]);return jQuery.inArray(val,tagslist)>=0};$.fn.importTags=function(str){id=$(this).attr("id");$("#"+id+"_tagsinput .tag").remove();$.fn.tagsInput.importTags(this,str)};$.fn.tagsInput=function(options){var settings=jQuery.extend({interactive:true,defaultText:"",minChars:0,width:"",height:"",autocomplete:{selectFirst:false},hide:true,delimiter:",",unique:true,removeWithBackspace:true,placeholderColor:"#666666",autosize:true,comfortZone:20,inputPadding:6*2},options);this.each(function(){if(settings.hide){$(this).hide()}var id=$(this).attr("id");if(!id||delimiter[$(this).attr("id")]){id=$(this).attr("id","tags"+(new Date).getTime()).attr("id")}var data=jQuery.extend({pid:id,real_input:"#"+id,holder:"#"+id+"_tagsinput",input_wrapper:"#"+id+"_addTag",fake_input:"#"+id+"_tag"},settings);delimiter[id]=data.delimiter;if(settings.onAddTag||settings.onRemoveTag||settings.onChange){tags_callbacks[id]=new Array;tags_callbacks[id]["onAddTag"]=settings.onAddTag;tags_callbacks[id]["onRemoveTag"]=settings.onRemoveTag;tags_callbacks[id]["onChange"]=settings.onChange}var containerClass=$("#"+id).attr("class").replace("tagsinput","");var markup='<div id="'+id+'_tagsinput" class="tagsinput '+containerClass+'"><div class="tagsinput-add-container" id="'+id+'_addTag"><div class="tagsinput-add"></div>';if(settings.interactive){markup=markup+'<input id="'+id+'_tag" value="" data-default="'+settings.defaultText+'" />'}markup=markup+"</div></div>";$(markup).insertAfter(this);$(data.holder).css("width",settings.width);$(data.holder).css("min-height",settings.height);$(data.holder).css("height","100%");if($(data.real_input).val()!=""){$.fn.tagsInput.importTags($(data.real_input),$(data.real_input).val())}if(settings.interactive){$(data.fake_input).val($(data.fake_input).attr("data-default"));$(data.fake_input).css("color",settings.placeholderColor);$(data.fake_input).resetAutosize(settings);$(data.holder).bind("click",data,function(event){$(event.data.fake_input).focus()});$(data.fake_input).bind("focus",data,function(event){if($(event.data.fake_input).val()==$(event.data.fake_input).attr("data-default")){$(event.data.fake_input).val("")}$(event.data.fake_input).css("color","#000000")});if(settings.autocomplete_url!=undefined){autocomplete_options={source:settings.autocomplete_url};for(attrname in settings.autocomplete){autocomplete_options[attrname]=settings.autocomplete[attrname]}if(jQuery.Autocompleter!==undefined){$(data.fake_input).autocomplete(settings.autocomplete_url,settings.autocomplete);$(data.fake_input).bind("result",data,function(event,data,formatted){if(data){$("#"+id).addTag(data[0]+"",{focus:true,unique:settings.unique})}})}else if(jQuery.ui.autocomplete!==undefined){$(data.fake_input).autocomplete(autocomplete_options);$(data.fake_input).bind("autocompleteselect",data,function(event,ui){$(event.data.real_input).addTag(ui.item.value,{focus:true,unique:settings.unique});return false})}}else{$(data.fake_input).bind("blur",data,function(event){var d=$(this).attr("data-default");if($(event.data.fake_input).val()!=""&&$(event.data.fake_input).val()!=d){if(event.data.minChars<=$(event.data.fake_input).val().length&&(!event.data.maxChars||event.data.maxChars>=$(event.data.fake_input).val().length))$(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:settings.unique})}else{$(event.data.fake_input).val($(event.data.fake_input).attr("data-default"));$(event.data.fake_input).css("color",settings.placeholderColor)}return false})}$(data.fake_input).bind("keypress",data,function(event){if(event.which==event.data.delimiter.charCodeAt(0)||event.which==13){event.preventDefault();if(event.data.minChars<=$(event.data.fake_input).val().length&&(!event.data.maxChars||event.data.maxChars>=$(event.data.fake_input).val().length))$(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:settings.unique});$(event.data.fake_input).resetAutosize(settings);return false}else if(event.data.autosize){$(event.data.fake_input).doAutosize(settings)}});data.removeWithBackspace&&$(data.fake_input).bind("keydown",function(event){if(event.keyCode==8&&$(this).val()==""){event.preventDefault();var last_tag=$(this).closest(".tagsinput").find(".tag:last").text();var id=$(this).attr("id").replace(/_tag$/,"");last_tag=last_tag.replace(/[\s\u00a0]+x$/,"");$("#"+id).removeTag(escape(last_tag));$(this).trigger("focus")}});$(data.fake_input).blur();if(data.unique){$(data.fake_input).keydown(function(event){if(event.keyCode==8||String.fromCharCode(event.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/)){$(this).removeClass("not_valid")}})}}});return this};$.fn.tagsInput.updateTagsField=function(obj,tagslist){var id=$(obj).attr("id");$(obj).val(tagslist.join(delimiter[id]))};$.fn.tagsInput.importTags=function(obj,val){$(obj).val("");var id=$(obj).attr("id");var tags=val.split(delimiter[id]);for(i=0;i<tags.length;i++){$(obj).addTag(tags[i],{focus:false,callback:false})}if(tags_callbacks[id]&&tags_callbacks[id]["onChange"]){var f=tags_callbacks[id]["onChange"];f.call(obj,obj,tags[i])}}}(jQuery);
$(document).ready(function(){$(".checkbox").on("click",function(){switchCheckbox($(this))})});function switchCheckbox($checkbox){if($checkbox.hasClass("master-checkbox")){switchAllCheckboxes($checkbox)}else{switchOneCheckbox($checkbox)}return 1}function switchOneCheckbox($checkbox){if($checkbox.hasClass("checked")){$("#master-checkbox-container").removeClass("checked");$("#master-checkbox-container").attr("data-state","off")}}function switchAllCheckboxes($master){if($master.attr("data-state")=="on"){$('.checkbox:not(".master-checkbox")').removeClass("checked");$('[data-toggle="checkbox"]').prop("checked",false);$master.attr("data-state","off")}else{$('.checkbox:not(".master-checkbox")').addClass("checked");$('[data-toggle="checkbox"]').prop("checked",true);$master.attr("data-state","on")}return 1}function checkIfNone(){return $('.checked:not(".master-checkbox")').length}function getCheckedIds(){var ids=[];$(".checked").each(function(){var $this=$(this);if($this.hasClass("checked")){ids.push($this.attr("data-id"))}});return ids}
var isTouch="ontouchstart"in document.documentElement;$(document).ready(function(){$("select").selectpicker();$(".select .dropdown-toggle").addClass("btn-info");$(".dropdown-menu").addClass("dropdown-inverse");$(".dropdown-arrow").addClass("dropdown-arrow-inverse");$("#tags").tagsInput();if(isTouch){$(".quick-action-container").css("min-height","0px")}});$(".label-loop").popover({placement:"left",trigger:"hover",html:true,content:function(){return $(this).find(".list-container").html()}});if(!isTouch){$(".checkbox-container td").mouseover(function(){$(this).find(".quick-action").show()});$(".checkbox-container td").mouseout(function(){$(this).find(".quick-action").hide()})}$(".quick-delete-action").on("click",function(event){event.preventDefault();var id=$(this).attr("data-id");if(confirm("Are you sure you want to delete this?")){deleteNode(id)}});$(".btn-checkbox-action").on("click",function(){token=$("#token").val();baseUrl=$("#baseUrl").val();var action=$(this).attr("data-action");var isChecked=checkIfNone();if(isChecked==0){alert("please select an item");return 0}var ids=getCheckedIds();switch(action){case"delete":ids.forEach(function(id){deleteNode(id,token)});break;case"edit":var id=ids.length==1?ids[0]:ids[1];window.location.href=baseUrl+"/node/"+id;break;default:alert("action not found");break}});$(".load-data-form").submit(function(event){event.preventDefault();loadData()});$(".load-data").click(function(){loadData()});$(".pag-next").click(function(){$page=$("#page");$page.val(parseInt($page.val())+1);loadData()});$(".pag-previous").click(function(){$page=$("#page");$page.val(parseInt($page.val())-1);loadData()});function deleteNode(id,token){$.post("/node/delete",{id:id,token:token},function(data,textStatus,xhr){if(textStatus=="success"){$("#tr-"+id).remove();$("#notices").html('<div id="content-type-alert" class="alert alert-info">Deleted</div>');$("#content-type-alert").show(0).delay(1e3).fadeOut(1e3)}else{alert("Unsuccessful delete. Please try again.")}})}function queryStringParams(){var str="";var page=document.getElementById("page").value;var state=document.getElementById("input-state").value;var startDate=document.getElementById("start-date").value;var endDate=document.getElementById("end-date").value;var tags=document.getElementById("tags").value;var categoryParent=document.getElementById("category-parent").value;var categorySub=document.getElementById("category-sub").value;var authorFirstName=document.getElementById("author-first-name").value;var authorLastName=document.getElementById("author-last-name").value;var search=document.getElementById("search").value;str=str+"?page="+page;if(state){str=str+"&state="+encodeURIComponent(state)}if(startDate){str=str+"&startDate="+encodeURIComponent(startDate)}if(endDate){str=str+"&endDate="+encodeURIComponent(endDate)}if(tags){str=str+"&tags="+encodeURIComponent(tags)}if(categoryParent){str=str+"&categoryParent="+encodeURIComponent(categoryParent)}if(categorySub){str=str+"&categorySub="+encodeURIComponent(categorySub)}if(authorFirstName){str=str+"&authorFirstName="+encodeURIComponent(authorFirstName)}if(authorLastName){str=str+"&authorLastName="+encodeURIComponent(authorLastName)}if(search){str=str+"&search="+encodeURIComponent(search)}return str}function loadData(){queryStringParams=queryStringParams();window.location=window.location.pathname+queryStringParams}$("a[disabled=disabled]").click(function(event){event.preventDefault();return false});$("#category-opener").on("click",function(){$("#category-container").toggle()});$("#category-cancel").on("click",function(){$("#category-parent").val("");$("#category-sub").val("");$("#category-opener-text").text("View Categories");$("#category-container").hide()});$("#category-confirm").on("click",function(){if(!$("#category-parent").val()&&!$("#category-sub").val()){$("#category-opener-text").text("View Categories")}else{$("#category-opener-text").text("Change Category")}$("#category-container").hide()});$("#tag-opener").on("click",function(){$("#tag-container").toggle()});$("#tag-cancel").on("click",function(){$("#tags").importTags("");$("#tag-opener-text").text("View All Tags");$("#tag-container").hide()});$("#tag-confirm").on("click",function(){if(!$("#tags").text()){$("#tag-opener-text").text("View All Tags")}else{$("#tag-opener-text").text("Change Tags")}$("#tag-container").hide()});$("#author-opener").on("click",function(){$("#author-container").toggle()});$("#author-cancel").on("click",function(){$("#author-first-name").val("");$("#author-last-name").val("");$("#author-opener-text").text("View All Authors");$("#author-container").hide()});$("#author-confirm").on("click",function(){if(!$("#author-first-name").val()&&!$("#author-last-name").val()){$("#author-opener-text").text("View All Authors")}else{$("#author-opener-text").text("Change Author")}$("#author-container").hide()});$(".open-filter-options").on("click",function(){$("#filter-options").toggleClass("hidden-phone",function(){if($(this).hasClass("hidden-phone")){$(".open-filter-options").text("open filter options")}else{$(".open-filter-options").text("close filter options").removeClass("btn-info")}})});