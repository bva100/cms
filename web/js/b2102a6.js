!function($){var Selectpicker=function(element,options,e){if(e){e.stopPropagation();e.preventDefault()}this.$element=$(element);this.$newElement=null;this.button=null;this.options=$.extend({},$.fn.selectpicker.defaults,this.$element.data(),typeof options=="object"&&options);if(this.options.title==null)this.options.title=this.$element.attr("title");this.val=Selectpicker.prototype.val;this.render=Selectpicker.prototype.render;this.init()};Selectpicker.prototype={constructor:Selectpicker,init:function(e){var _this=this;this.$element.hide();this.multiple=this.$element.prop("multiple");var classList=this.$element.attr("class")!==undefined?this.$element.attr("class").split(/\s+/):"";var id=this.$element.attr("id");this.$element.after(this.createView());this.$newElement=this.$element.next(".select");var select=this.$newElement;var menu=this.$newElement.find(".dropdown-menu");var menuArrow=this.$newElement.find(".dropdown-arrow");var menuA=menu.find("li > a");var liHeight=select.addClass("open").find(".dropdown-menu li > a").outerHeight();select.removeClass("open");var divHeight=menu.find("li .divider").outerHeight(true);var selectOffset_top=this.$newElement.offset().top;var size=0;var menuHeight=0;var selectHeight=this.$newElement.outerHeight();this.button=this.$newElement.find("> button");if(id!==undefined){this.button.attr("id",id);$('label[for="'+id+'"]').click(function(){select.find("button#"+id).focus()})}for(var i=0;i<classList.length;i++){if(classList[i]!="selectpicker"){this.$newElement.addClass(classList[i])}}if(this.multiple){this.$newElement.addClass("select-multiple")}this.button.addClass(this.options.style);menu.addClass(this.options.menuStyle);menuArrow.addClass(function(){if(_this.options.menuStyle){return _this.options.menuStyle.replace("dropdown-","dropdown-arrow-")}});this.checkDisabled();this.checkTabIndex();this.clickListener();var menuPadding=parseInt(menu.css("padding-top"))+parseInt(menu.css("padding-bottom"))+parseInt(menu.css("border-top-width"))+parseInt(menu.css("border-bottom-width"));if(this.options.size=="auto"){function getSize(){var selectOffset_top_scroll=selectOffset_top-$(window).scrollTop();var windowHeight=window.innerHeight;var menuExtras=menuPadding+parseInt(menu.css("margin-top"))+parseInt(menu.css("margin-bottom"))+2;var selectOffset_bot=windowHeight-selectOffset_top_scroll-selectHeight-menuExtras;menuHeight=selectOffset_bot;if(select.hasClass("dropup")){menuHeight=selectOffset_top_scroll-menuExtras}menu.css({"max-height":menuHeight+"px","overflow-y":"auto","min-height":liHeight*3+"px"})}getSize();$(window).resize(getSize);$(window).scroll(getSize);this.$element.bind("DOMNodeInserted",getSize)}else if(this.options.size&&this.options.size!="auto"&&menu.find("li").length>this.options.size){var optIndex=menu.find("li > *").filter(":not(.divider)").slice(0,this.options.size).last().parent().index();var divLength=menu.find("li").slice(0,optIndex+1).find(".divider").length;menuHeight=liHeight*this.options.size+divLength*divHeight+menuPadding;menu.css({"max-height":menuHeight+"px","overflow-y":"scroll"})}this.$element.bind("DOMNodeInserted",$.proxy(this.reloadLi,this));this.render()},createDropdown:function(){var drop="<div class='btn-group select'>"+"<i class='dropdown-arrow'></i>"+"<button class='btn dropdown-toggle clearfix' data-toggle='dropdown'>"+"<span class='filter-option pull-left'></span>&nbsp;"+"<span class='caret'></span>"+"</button>"+"<ul class='dropdown-menu' role='menu'>"+"</ul>"+"</div>";return $(drop)},createView:function(){var $drop=this.createDropdown();var $li=this.createLi();$drop.find("ul").append($li);return $drop},reloadLi:function(){this.destroyLi();$li=this.createLi();this.$newElement.find("ul").append($li);this.render()},destroyLi:function(){this.$newElement.find("li").remove()},createLi:function(){var _this=this;var _li=[];var _liA=[];var _liHtml="";this.$element.find("option").each(function(){_li.push($(this).text())});this.$element.find("option").each(function(index){var optionClass=$(this).attr("class")!==undefined?$(this).attr("class"):"";var text=$(this).text();var subtext=$(this).data("subtext")!==undefined?'<small class="muted">'+$(this).data("subtext")+"</small>":"";text+=subtext;if($(this).parent().is("optgroup")&&$(this).data("divider")!=true){if($(this).index()==0){var label=$(this).parent().attr("label");var labelSubtext=$(this).parent().data("subtext")!==undefined?'<small class="muted">'+$(this).parent().data("subtext")+"</small>":"";label+=labelSubtext;if($(this)[0].index!=0){_liA.push('<div class="divider"></div>'+"<dt>"+label+"</dt>"+_this.createA(text,"opt "+optionClass))}else{_liA.push("<dt>"+label+"</dt>"+_this.createA(text,"opt "+optionClass))}}else{_liA.push(_this.createA(text,"opt "+optionClass))}}else if($(this).data("divider")==true){_liA.push('<div class="divider"></div>')}else{_liA.push(_this.createA(text,optionClass))}});if(_li.length>0){for(var i=0;i<_li.length;i++){var $option=this.$element.find("option").eq(i);_liHtml+="<li rel="+i+">"+_liA[i]+"</li>"}}if(this.$element.find("option:selected").length==0&&!_this.options.title){this.$element.find("option").eq(0).prop("selected",true).attr("selected","selected")}return $(_liHtml)},createA:function(test,classes){return'<a tabindex="-1" href="#" class="'+classes+'">'+'<span class="pull-left">'+test+"</span>"+"</a>"},render:function(){var _this=this;if(this.options.width=="auto"){var ulWidth=this.$newElement.find(".dropdown-menu").css("width");this.$newElement.css("width",ulWidth)}else if(this.options.width&&this.options.width!="auto"){this.$newElement.css("width",this.options.width)}this.$element.find("option").each(function(index){_this.setDisabled(index,$(this).is(":disabled")||$(this).parent().is(":disabled"));_this.setSelected(index,$(this).is(":selected"))});var selectedItems=this.$element.find("option:selected").map(function(index,value){if($(this).attr("title")!=undefined){return $(this).attr("title")}else{return $(this).text()}}).toArray();var title=selectedItems.join(", ");if(_this.multiple&&_this.options.selectedTextFormat.indexOf("count")>-1){var max=_this.options.selectedTextFormat.split(">");if(max.length>1&&selectedItems.length>max[1]||max.length==1&&selectedItems.length>=2){title=selectedItems.length+" of "+this.$element.find("option").length+" selected"}}if(!title){title=_this.options.title!=undefined?_this.options.title:_this.options.noneSelectedText}this.$element.next(".select").find(".filter-option").html(title)},setSelected:function(index,selected){if(selected){this.$newElement.find("li").eq(index).addClass("selected")}else{this.$newElement.find("li").eq(index).removeClass("selected")}},setDisabled:function(index,disabled){if(disabled){this.$newElement.find("li").eq(index).addClass("disabled")}else{this.$newElement.find("li").eq(index).removeClass("disabled")}},checkDisabled:function(){if(this.$element.is(":disabled")){this.button.addClass("disabled");this.button.click(function(e){e.preventDefault()})}},checkTabIndex:function(){if(this.$element.is("[tabindex]")){var tabindex=this.$element.attr("tabindex");this.button.attr("tabindex",tabindex)}},clickListener:function(){var _this=this;$("body").on("touchstart.dropdown",".dropdown-menu",function(e){e.stopPropagation()});this.$newElement.on("click","li a",function(e){var clickedIndex=$(this).parent().index(),$this=$(this).parent(),$select=$this.parents(".select");if(_this.multiple){e.stopPropagation()}e.preventDefault();if($select.prev("select").not(":disabled")&&!$(this).parent().hasClass("disabled")){if(!_this.multiple){$select.prev("select").find("option").removeAttr("selected");$select.prev("select").find("option").eq(clickedIndex).prop("selected",true).attr("selected","selected")}else{var selected=$select.prev("select").find("option").eq(clickedIndex).prop("selected");if(selected){$select.prev("select").find("option").eq(clickedIndex).removeAttr("selected")}else{$select.prev("select").find("option").eq(clickedIndex).prop("selected",true).attr("selected","selected")}}$select.find(".filter-option").html($this.text());$select.find("button").focus();$select.prev("select").trigger("change")}});this.$newElement.on("click","li.disabled a, li dt, li .divider",function(e){e.preventDefault();e.stopPropagation();$select=$(this).parent().parents(".select");$select.find("button").focus()});this.$element.on("change",function(e){_this.render()})},val:function(value){if(value!=undefined){this.$element.val(value);this.$element.trigger("change");return this.$element}else{return this.$element.val()}}};$.fn.selectpicker=function(option,event){var args=arguments;var value;var chain=this.each(function(){var $this=$(this),data=$this.data("selectpicker"),options=typeof option=="object"&&option;if(!data){$this.data("selectpicker",data=new Selectpicker(this,options,event))}else{for(var i in option){data[i]=option[i]}}if(typeof option=="string"){property=option;if(data[property]instanceof Function){[].shift.apply(args);value=data[property].apply(data,args)}else{value=data[property]}}});if(value!=undefined){return value}else{return chain}};$.fn.selectpicker.defaults={style:null,size:"auto",title:null,selectedTextFormat:"values",noneSelectedText:"Nothing selected",width:null,menuStyle:null,toggleSize:null}}(window.jQuery);
var baseUrl=document.getElementById("base-url").value;$(document).ready(function(){$("select").selectpicker();$(".select .dropdown-toggle").addClass("btn-inverse");$(".dropdown-menu").addClass("dropdown-inverse");$(".dropdown-arrow").addClass("dropdown-arrow-inverse")});$("#api-form").on("submit",function(event){event.preventDefault();$container=$("#results");$container.html("");loadApiData(getParams(),$container)});function getParams(){var params={};params.endpoint=document.getElementById("input-endpoint").value;params.domain=document.getElementById("input-domain").value;params.path=document.getElementById("input-path").value;return params}function loadApiData(params,$container){var url=createUrl(params);$container.html('<a href="'+url+'" style="font-size:22px;">'+url+"</a>")}function createUrl(params){return"http://"+params.domain+baseUrl+"/api/v1/"+params.endpoint+"/"+params.path}function loadLoop(url,params,$container){$.get(url,{},function(data,textStatus,xhr){if(xhr.status===200){var nodes=data.loop;switch(params.output){case"raw":$container.text(JSON.stringify(data));$container.append("<hr><h2>Loop node data:</h2>"+JSON.stringify(data.node));break;case"text":for(var i=0;i<nodes.length;i++){$container.append("<h2>Node "+i+'</h2><div class="row-fluid" style="border-bottom: 1px solid dimgrey; margin: 10px 0px 20px 0px; padding: 30px; 0px;"><div class="span12">'+nodes[i].view.text+"</div></div>")}break;case"html":default:for(var i=0;i<nodes.length;i++){$container.append("<h2>Node "+i+'</h2><div class="row-fluid" style="border-bottom: 1px solid dimgrey; margin: 10px 0px 20px 0px; padding: 30px; 0px;"><div class="span12">'+nodes[i].view.html+"</div></div>")}break}return 1}else{alert("failed");return 0}}).fail(function(data,textStatus,xhr){$container.html(createResponseAlert(xhr));return 0})}function loadNode(url,params,$container){$.get(url,{},function(data,textStatus,xhr){if(xhr.status===200){var node=data.node;switch(params.output){case"raw":$container.text(JSON.stringify(data));$container.append("<hr><h2>Loop node data:</h2>"+JSON.stringify(data.node));break;case"text":$container.html(node.view.text);break;case"html":default:$container.html(node.view.html);break}}else{alert("failed");return 0}}).fail(function(data,textStatus,xhr){$container.html(createResponseAlert(xhr));return 0})}function createResponseAlert(xhr){return'<div class="row-fluid"><div class="alert alert-danger span"><i class="icon-warning">'+xhr+"</i></div></div>"}