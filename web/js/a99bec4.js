$(document).ready(function(){var a=$(".alert").length>0;if(a){$("#notice-container").show(0).delay(1000).fadeOut(1000)}});
$("#domain-container").on("click",function(){$("#domain-selector-container").toggle()});$(".radio-domain").click(function(){var a=$(this).find("input").attr("data-domain");updateDomain(a);$("#domain-selector-container").hide(400)});$(".radio-slug").on("click",function(){var c=$(this).attr("data-slug-type");var b=$("#title");if(c==="title"){b.attr("data-slug-title","on");var d=b.val();$("#slug-selector-container").hide()}else{var a=$("#input-custom-slug");b.attr("data-slug-title","off");a.show();$("#button-custom-slug").show();var d=a.val()}updateSlug(d)});$("#slug-container").on("click",function(){$("#slug-selector-container").toggle()});$("#title").blur(function(){if($(this).attr("data-slug-title")==="on"){var a=$(this).val();updateSlug(a)}});$("#input-custom-slug").blur(function(){var a=$(this).val();updateSlug(a);$("#slug-selector-container").hide()});function updateSlug(a){a=a.replace(/\s+/g,"-").toLowerCase();$("#slug-container").text(a);$("#slug").val(a)}function updateDomain(a){$("#domain-container").text(a);$("#domain").val(a)};
!function(b){var c=function(e,d){this.init(e,d)};c.prototype={constructor:c,init:function(f,d){var e=this.$element=b(f);this.options=b.extend({},b.fn.radio.defaults,d);e.before(this.options.template);this.setState()},setState:function(){var d=this.$element,e=d.closest(".radio");d.prop("disabled")&&e.addClass("disabled");d.prop("checked")&&e.addClass("checked")},toggle:function(){var m="disabled",h="checked",g=this.$element,i=g.prop(h),k=g.closest(".radio"),l=g.closest("form").length?g.closest("form"):g.closest("body"),f=l.find(':radio[name="'+g.attr("name")+'"]'),j=b.Event("toggle");f.not(g).each(function(){var d=b(this),e=b(this).closest(".radio");if(d.prop(m)==false){e.removeClass(h)&&d.attr(h,false).trigger("change")}});if(g.prop(m)==false){if(i==false){k.addClass(h)&&g.attr(h,true)}g.trigger(j);if(i!==g.prop(h)){g.trigger("change")}}},setCheck:function(h){var d="checked",m=this.$element,g=m.closest(".radio"),f=h=="check"?true:false,j=m.prop(d),k=m.closest("form").length?m.closest("form"):m.closest("body"),l=k.find(':radio[name="'+m.attr("name")+'"]'),i=b.Event(h);l.not(m).each(function(){var e=b(this),n=b(this).closest(".radio");n.removeClass(d)&&e.removeAttr(d)});g[f?"addClass":"removeClass"](d)&&f?m.attr(d,true):m.removeAttr(d);m.trigger(i);if(j!==m.prop(d)){m.trigger("change")}}};var a=b.fn.radio;b.fn.radio=function(d){return this.each(function(){var g=b(this),f=g.data("radio"),e=b.extend({},b.fn.radio.defaults,g.data(),typeof d=="object"&&d);if(!f){g.data("radio",(f=new c(this,e)))}if(d=="toggle"){f.toggle()}if(d=="check"||d=="uncheck"){f.setCheck(d)}else{if(d){f.setState()}}})};b.fn.radio.defaults={template:'<span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span>'};b.fn.radio.noConflict=function(){b.fn.radio=a;return this};b(document).on("click.radio.data-api","[data-toggle^=radio], .radio",function(f){var d=b(f.target);if(f.target.tagName!="A"){f&&f.preventDefault()&&f.stopPropagation();if(!d.hasClass("radio")){d=d.closest(".radio")}d.find(":radio").radio("toggle")}});b(window).on("load",function(){b('[data-toggle="radio"]').each(function(){var d=b(this);d.radio()})})}(window.jQuery);