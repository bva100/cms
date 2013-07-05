!function(b){var a=function(d,c,f){if(f){f.stopPropagation();f.preventDefault()}this.$element=b(d);this.$newElement=null;this.button=null;this.options=b.extend({},b.fn.selectpicker.defaults,this.$element.data(),typeof c=="object"&&c);if(this.options.title==null){this.options.title=this.$element.attr("title")}this.val=a.prototype.val;this.render=a.prototype.render;this.init()};a.prototype={constructor:a,init:function(u){var s=this;this.$element.hide();this.multiple=this.$element.prop("multiple");var w=this.$element.attr("class")!==undefined?this.$element.attr("class").split(/\s+/):"";var p=this.$element.attr("id");this.$element.after(this.createView());this.$newElement=this.$element.next(".select");var q=this.$newElement;var d=this.$newElement.find(".dropdown-menu");var j=this.$newElement.find(".dropdown-arrow");var n=d.find("li > a");var g=q.addClass("open").find(".dropdown-menu li > a").outerHeight();q.removeClass("open");var h=d.find("li .divider").outerHeight(true);var k=this.$newElement.offset().top;var m=0;var o=0;var v=this.$newElement.outerHeight();this.button=this.$newElement.find("> button");if(p!==undefined){this.button.attr("id",p);b('label[for="'+p+'"]').click(function(){q.find("button#"+p).focus()})}for(var t=0;t<w.length;t++){if(w[t]!="selectpicker"){this.$newElement.addClass(w[t])}}if(this.multiple){this.$newElement.addClass("select-multiple")}this.button.addClass(this.options.style);d.addClass(this.options.menuStyle);j.addClass(function(){if(s.options.menuStyle){return s.options.menuStyle.replace("dropdown-","dropdown-arrow-")}});this.checkDisabled();this.checkTabIndex();this.clickListener();var r=parseInt(d.css("padding-top"))+parseInt(d.css("padding-bottom"))+parseInt(d.css("border-top-width"))+parseInt(d.css("border-bottom-width"));if(this.options.size=="auto"){function f(){var e=k-b(window).scrollTop();var y=window.innerHeight;var i=r+parseInt(d.css("margin-top"))+parseInt(d.css("margin-bottom"))+2;var x=y-e-v-i;o=x;if(q.hasClass("dropup")){o=e-i}d.css({"max-height":o+"px","overflow-y":"auto","min-height":g*3+"px"})}f();b(window).resize(f);b(window).scroll(f);this.$element.bind("DOMNodeInserted",f)}else{if(this.options.size&&this.options.size!="auto"&&d.find("li").length>this.options.size){var l=d.find("li > *").filter(":not(.divider)").slice(0,this.options.size).last().parent().index();var c=d.find("li").slice(0,l+1).find(".divider").length;o=g*this.options.size+c*h+r;d.css({"max-height":o+"px","overflow-y":"scroll"})}}this.$element.bind("DOMNodeInserted",b.proxy(this.reloadLi,this));this.render()},createDropdown:function(){var c="<div class='btn-group select'><i class='dropdown-arrow'></i><button class='btn dropdown-toggle clearfix' data-toggle='dropdown'><span class='filter-option pull-left'></span>&nbsp;<span class='caret'></span></button><ul class='dropdown-menu' role='menu'></ul></div>";return b(c)},createView:function(){var c=this.createDropdown();var d=this.createLi();c.find("ul").append(d);return c},reloadLi:function(){this.destroyLi();$li=this.createLi();this.$newElement.find("ul").append($li);this.render()},destroyLi:function(){this.$newElement.find("li").remove()},createLi:function(){var h=this;var e=[];var g=[];var c="";this.$element.find("option").each(function(){e.push(b(this).text())});this.$element.find("option").each(function(k){var n=b(this).attr("class")!==undefined?b(this).attr("class"):"";var m=b(this).text();var l=b(this).data("subtext")!==undefined?'<small class="muted">'+b(this).data("subtext")+"</small>":"";m+=l;if(b(this).parent().is("optgroup")&&b(this).data("divider")!=true){if(b(this).index()==0){var j=b(this).parent().attr("label");var i=b(this).parent().data("subtext")!==undefined?'<small class="muted">'+b(this).parent().data("subtext")+"</small>":"";j+=i;if(b(this)[0].index!=0){g.push('<div class="divider"></div><dt>'+j+"</dt>"+h.createA(m,"opt "+n))}else{g.push("<dt>"+j+"</dt>"+h.createA(m,"opt "+n))}}else{g.push(h.createA(m,"opt "+n))}}else{if(b(this).data("divider")==true){g.push('<div class="divider"></div>')}else{g.push(h.createA(m,n))}}});if(e.length>0){for(var d=0;d<e.length;d++){var f=this.$element.find("option").eq(d);c+="<li rel="+d+">"+g[d]+"</li>"}}if(this.$element.find("option:selected").length==0&&!h.options.title){this.$element.find("option").eq(0).prop("selected",true).attr("selected","selected")}return b(c)},createA:function(d,c){return'<a tabindex="-1" href="#" class="'+c+'"><span class="pull-left">'+d+"</span></a>"},render:function(){var g=this;if(this.options.width=="auto"){var d=this.$newElement.find(".dropdown-menu").css("width");this.$newElement.css("width",d)}else{if(this.options.width&&this.options.width!="auto"){this.$newElement.css("width",this.options.width)}}this.$element.find("option").each(function(h){g.setDisabled(h,b(this).is(":disabled")||b(this).parent().is(":disabled"));g.setSelected(h,b(this).is(":selected"))});var f=this.$element.find("option:selected").map(function(h,i){if(b(this).attr("title")!=undefined){return b(this).attr("title")}else{return b(this).text()}}).toArray();var e=f.join(", ");if(g.multiple&&g.options.selectedTextFormat.indexOf("count")>-1){var c=g.options.selectedTextFormat.split(">");if((c.length>1&&f.length>c[1])||(c.length==1&&f.length>=2)){e=f.length+" of "+this.$element.find("option").length+" selected"}}if(!e){e=g.options.title!=undefined?g.options.title:g.options.noneSelectedText}this.$element.next(".select").find(".filter-option").html(e)},setSelected:function(c,d){if(d){this.$newElement.find("li").eq(c).addClass("selected")}else{this.$newElement.find("li").eq(c).removeClass("selected")}},setDisabled:function(c,d){if(d){this.$newElement.find("li").eq(c).addClass("disabled")}else{this.$newElement.find("li").eq(c).removeClass("disabled")}},checkDisabled:function(){if(this.$element.is(":disabled")){this.button.addClass("disabled");this.button.click(function(c){c.preventDefault()})}},checkTabIndex:function(){if(this.$element.is("[tabindex]")){var c=this.$element.attr("tabindex");this.button.attr("tabindex",c)}},clickListener:function(){var c=this;b("body").on("touchstart.dropdown",".dropdown-menu",function(d){d.stopPropagation()});this.$newElement.on("click","li a",function(i){var g=b(this).parent().index(),h=b(this).parent(),d=h.parents(".select");if(c.multiple){i.stopPropagation()}i.preventDefault();if(d.prev("select").not(":disabled")&&!b(this).parent().hasClass("disabled")){if(!c.multiple){d.prev("select").find("option").removeAttr("selected");d.prev("select").find("option").eq(g).prop("selected",true).attr("selected","selected")}else{var f=d.prev("select").find("option").eq(g).prop("selected");if(f){d.prev("select").find("option").eq(g).removeAttr("selected")}else{d.prev("select").find("option").eq(g).prop("selected",true).attr("selected","selected")}}d.find(".filter-option").html(h.text());d.find("button").focus();d.prev("select").trigger("change")}});this.$newElement.on("click","li.disabled a, li dt, li .divider",function(d){d.preventDefault();d.stopPropagation();$select=b(this).parent().parents(".select");$select.find("button").focus()});this.$element.on("change",function(d){c.render()})},val:function(c){if(c!=undefined){this.$element.val(c);this.$element.trigger("change");return this.$element}else{return this.$element.val()}}};b.fn.selectpicker=function(e,f){var c=arguments;var g;var d=this.each(function(){var l=b(this),k=l.data("selectpicker"),h=typeof e=="object"&&e;if(!k){l.data("selectpicker",(k=new a(this,h,f)))}else{for(var j in e){k[j]=e[j]}}if(typeof e=="string"){property=e;if(k[property] instanceof Function){[].shift.apply(c);g=k[property].apply(k,c)}else{g=k[property]}}});if(g!=undefined){return g}else{return d}};b.fn.selectpicker.defaults={style:null,size:"auto",title:null,selectedTextFormat:"values",noneSelectedText:"Nothing selected",width:null,menuStyle:null,toggleSize:null}}(window.jQuery);
!function(b){var c=function(e,d){this.init(e,d)};c.prototype={constructor:c,init:function(f,d){var e=this.$element=b(f);this.options=b.extend({},b.fn.checkbox.defaults,d);e.before(this.options.template);this.setState()},setState:function(){var d=this.$element,e=d.closest(".checkbox");d.prop("disabled")&&e.addClass("disabled");d.prop("checked")&&e.addClass("checked")},toggle:function(){var f="checked",d=this.$element,i=d.closest(".checkbox"),g=d.prop(f),h=b.Event("toggle");if(d.prop("disabled")==false){i.toggleClass(f)&&g?d.removeAttr(f):d.attr(f,true);d.trigger(h).trigger("change")}},setCheck:function(i){var l="disabled",h="checked",g=this.$element,k=g.closest(".checkbox"),f=i=="check"?true:false,j=b.Event(i);k[f?"addClass":"removeClass"](h)&&f?g.attr(h,true):g.removeAttr(h);g.trigger(j).trigger("change")}};var a=b.fn.checkbox;b.fn.checkbox=function(d){return this.each(function(){var g=b(this),f=g.data("checkbox"),e=b.extend({},b.fn.checkbox.defaults,g.data(),typeof d=="object"&&d);if(!f){g.data("checkbox",(f=new c(this,e)))}if(d=="toggle"){f.toggle()}if(d=="check"||d=="uncheck"){f.setCheck(d)}else{if(d){f.setState()}}})};b.fn.checkbox.defaults={template:'<span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span>'};b.fn.checkbox.noConflict=function(){b.fn.checkbox=a;return this};b(document).on("click.checkbox.data-api","[data-toggle^=checkbox], .checkbox",function(f){var d=b(f.target);if(f.target.tagName!="A"){f&&f.preventDefault()&&f.stopPropagation();if(!d.hasClass("checkbox")){d=d.closest(".checkbox")}d.find(":checkbox").checkbox("toggle")}});b(window).on("load",function(){b('[data-toggle="checkbox"]').each(function(){var d=b(this);d.checkbox()})})}(window.jQuery);
$(document).ready(function(){$(".flatui-calendar-input-start").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(a){$("#end-date").datepicker("option","minDate",a);$("#end-date").datepicker("show")}});$(".flatui-calendar-input-end").datepicker({showOtherMonths:true,selectOtherMonths:true,onClose:function(a){$("#start-date").datepicker("option","maxDate",a)}});$(".flatui-calendar").on("click",function(){$("#flatui-calendar-input-container").toggle();$(this).blur()});$("#flatui-calendar-confirm-dates").on("click",function(){$(".flatui-calendar-text").text("Change Dates");$("#flatui-calendar-input-container").hide()});$("#flatui-calendar-cancel-dates").on("click",function(){$("#start-date").val("");$("#end-date").val("");$(".flatui-calendar-text").text("View All Dates");$("#flatui-calendar-input-container").hide()})});
btnCallbackClass=".btn-checkbox-action";checkboxContainer="btn-checkbox-callback";$(document).ready(function(){$(".checkbox").on("click",function(){switchCheckbox($(this))});$(".btn-checkbox-action").on("click",function(){token=$("#token").val();baseUrl=$("#baseRoot").val();var b=$(this).attr("data-action");var d=checkIfNone();if(d==0){alert("please select an item");return 0}var a=getCheckedIds();switch(b){case"delete":a.forEach(function(e){deleteNode(e,token)});break;case"edit":var c=a.length==1?a[0]:a[1];window.location.href=baseUrl+"/node/"+c;break;default:alert("action not found");break}})});function switchCheckbox(a){if(a.hasClass("master-checkbox")){switchAllCheckboxes(a)}else{switchOneCheckbox(a)}return 1}function switchOneCheckbox(a){if(a.hasClass("checked")){$("#master-checkbox-container").removeClass("checked");$("#master-checkbox-container").attr("data-state","off")}}function switchAllCheckboxes(a){if(a.attr("data-state")=="on"){$('.checkbox:not(".master-checkbox")').removeClass("checked");$('[data-toggle="checkbox"]').prop("checked",false);a.attr("data-state","off")}else{$('.checkbox:not(".master-checkbox")').addClass("checked");$('[data-toggle="checkbox"]').prop("checked",true);a.attr("data-state","on")}return 1}function checkIfNone(){return $('.checked:not(".master-checkbox")').length}function getCheckedIds(){var a=[];$(".checked").each(function(){var b=$(this);if(b.hasClass("checked")){a.push(b.attr("data-id"))}});return a}function deleteNode(b,a){$.post("/node/delete",{id:b,token:a},function(c,e,d){if(e=="success"){$("#tr-"+b).remove();$("#notices").html('<div id="content-type-alert" class="alert alert-info">Deleted</div>');$("#content-type-alert").show(0).delay(1000).fadeOut(1000)}else{alert("Unsuccessful delete. Please try again.")}})};
$(document).ready(function(){$("select").selectpicker();$(".select .dropdown-toggle").addClass("btn-info");$(".dropdown-menu").addClass("dropdown-inverse")});