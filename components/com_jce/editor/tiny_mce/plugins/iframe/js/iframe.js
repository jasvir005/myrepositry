/*  
 * IFrame                 2.0.1
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    07 May 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
var IframeDialog={settings:{},init:function(){var self=this,ed=tinyMCEPopup.editor,s=ed.selection,n=s.getNode(),v,u,data={};tinyMCEPopup.resizeToInnerSize();tinyMCEPopup.restoreSelection();TinyMCE_Utils.fillClassList('classlist');$.Plugin.init();$('#insert').click(function(){self.insert();});if(/mceItemIframe/.test(n.className)){data=$.parseJSON(ed.dom.getAttrib(n,'data-mce-json'));if(data&&data.iframe){$('#insert').button('option','label',tinyMCEPopup.getLang('update','Update',true));$.each(data.iframe,function(k,v){if($('#'+k).is(':checkbox')){$('#'+k).prop('checked',!!v);}else{if(k=='src'){v=ed.convertURL(v);}
$('#'+k).val(v);}});$.each(['class','width','height','style','id','longdesc','align'],function(i,k){v=ed.dom.getAttrib(n,k);switch(k){case'class':v=tinymce.trim(v.replace(/\s?mceItemIframe/g,''));$('#classes, #classlist').val(v);break;case'width':case'height':v=self.getAttrib(n,k);u=/%/.test(v)?'%':'px';v=v.replace(/[^0-9]/g,'');$('#'+k).val(v).data('tmp',v);$('#'+k+'_unit').val(u);break;case'align':$('#'+k).val(self.getAttrib(n,k));break;default:$('#'+k).val(v);break;}
if($('#width_unit').val()!==$('#height_unit').val()){$('#constrain').prop('checked',false);}});$.each(['top','right','bottom','left'],function(i,k){v=self.getAttrib(n,'margin-'+k);$('#margin_'+k).val(v);});}}else{$.Plugin.setDefaults(this.settings.defaults);}
this.setMargins(true);this.updateStyles();},getAttrib:function(e,at){var ed=tinyMCEPopup.editor,v,v2;switch(at){case'width':case'height':return ed.dom.getAttrib(e,at)||ed.dom.getStyle(n,at)||'';break;case'align':if(v=ed.dom.getAttrib(e,'align')){return v;}
if(v=ed.dom.getStyle(e,'float')){return v;}
if(v=ed.dom.getStyle(e,'vertical-align')){return v;}
break;case'margin-top':case'margin-bottom':if(v=ed.dom.getStyle(e,at)){if(v=='auto'){return v;}
return parseInt(v.replace(/[^0-9-]/g,''));}
if(v=ed.dom.getAttrib(e,'vspace')){return parseInt(v.replace(/[^0-9]/g,''));}
break;case'margin-left':case'margin-right':if(v=ed.dom.getStyle(e,at)){if(v=='auto'){return v;}
return parseInt(v.replace(/[^0-9-]/g,''));}
if(v=ed.dom.getAttrib(e,'hspace')){return parseInt(v.replace(/[^0-9]/g,''));}
break;}},checkPrefix:function(n){var self=this,v=$(n).val();if(/^\s*www./i.test(v)){$.Dialog.confirm(tinyMCEPopup.getLang('iframe_dlg.is_external','The URL you entered seems to be an external link, do you want to add the required http:// prefix?'),function(state){if(state){$(n).val('http://'+v);}
self.insert();});}else{this.insertAndClose();}},insert:function(){var ed=tinyMCEPopup.editor;AutoValidator.validate(document);if($('#src').val()===''){$.Dialog.alert(tinyMCEPopup.getLang('iframe_dlg.no_src','Please enter a url for the iframe'));return false;}
if($('#width').val()===''||$('#height').val()===''){$.Dialog.alert(tinyMCEPopup.getLang('iframe_dlg.no_dimensions','Please enter a width and height for the iframe'));return false;}
return this.checkPrefix($('#src'));},insertAndClose:function(){tinyMCEPopup.restoreSelection();var ed=tinyMCEPopup.editor,args={},n=ed.selection.getNode();tinymce.each(['classes','style','id','longdesc','title'],function(k){var v=$('#'+k).val();if(v!==''){if(k=='classes'){k='class';}
args[k]=v;}});tinymce.extend(args,{src:tinyMCEPopup.getWindowArg('plugin_url')+'/img/trans.gif','data-mce-json':this.serializeParameters(),width:$('#width').val()+$('#width_unit').val(),height:$('#height').val()+$('#height_unit').val()});if(n&&ed.dom.is(n,'img.mceItemIframe')){ed.dom.setAttribs(n,args);ed.dom.addClass(n,'mceItemIframe');}else{ed.execCommand('mceInsertContent',false,'<img id="__mce_tmp" src="javascript:;" />',{skip_undo:1});n=ed.dom.get('__mce_tmp');ed.dom.setAttrib('__mce_tmp','id','');ed.dom.setAttribs(n,args);ed.dom.addClass(n,'mceItemIframe');ed.undoManager.add();}
tinyMCEPopup.close();},serializeParameters:function(){var ed=tinyMCEPopup.editor,data={};tinymce.each(['src','name','scrolling','marginwidth','marginheight','frameborder','allowtransparency'],function(k){var v=$('#'+k).val();if(v!==''){if(k=='src'){v=v.replace(/&amp;/gi,'&');v=ed.convertURL(v);}
data[k]=v;}});var o={'iframe':data};return $.JSON.serialize(o);},setMargins:function(init){var x=0,s=false;var v=$('#margin_top').val();var $elms=$('#margin_right, #margin_bottom, #margin_left');if(init){$elms.each(function(){if($(this).val()===v){x++;}});s=(x==$elms.length);$elms.prop('disabled',s).prev('label').toggleClass('disabled',s);$('#margin_check').prop('checked',s);}else{s=$('#margin_check').is(':checked');$elms.each(function(){if(s){if(v===''){$('#margin_right, #margin_bottom, #margin_left').each(function(){if(v===''&&$(this).val()!==''){v=$(this).val();}});}
$(this).val(v);}
$(this).prop('disabled',s).prev('label').toggleClass('disabled',s);});$('#margin_top').val(v);this.updateStyles();}},setClasses:function(v){return $.Plugin.setClasses(v);},setDimensions:function(a,b){var tmp,$a=$('#'+a),av=$a.val(),$b=$('#'+b),bv=$b.val(),au=$('#'+a+'_unit').val(),bu=$('#'+b+'_unit').val();if($('#constrain').is(':checked')){if(av&&bv&&$a.data('tmp')&&$b.data('tmp')){if(au=='%'&&bu=='%'){tmp=av;}else if(au=='%'){tmp=Math.round(bv*av/100);}else{tmp=(bv/$a.data('tmp')*av).toFixed(0);}
$b.val(tmp).data('tmp',tmp);}}
$a.data('tmp',av);},setDimensionUnit:function(a,b){var $a=$('#'+a),av=$a.val(),$b=$('#'+b),bv=$b.val(),au=$('#'+a+'_unit').val(),bu=$('#'+b+'_unit').val();if($('#constrain').is(':checked')){if(av&&bv&&$a.data('tmp')&&$b.data('tmp')){$('#'+b+'_unit').val(au);if(au=='px'){$a.val(Math.round(av*$a.data('tmp')/100));$b.val(Math.round(bv*$b.data('tmp')/100));}else{$a.val(Math.round(av/$a.data('tmp')*100));$b.val(Math.round(bv/$b.data('tmp')*100));}}}},setStyles:function(){var self=this,ed=tinyMCEPopup,img=$('#sample');$(img).attr('style',$('#style').val());tinymce.each(['top','right','bottom','left'],function(o){var v=parseFloat($(img).css('margin-'+o));$('#margin_'+o).val(v);});$('#align',$(img).attr('align')||$(img).css('float')||$(img).css('vertical-align'));},updateStyles:function(){var ed=tinyMCEPopup,st,v,br,img=$('#sample');$(img).attr('style',$('#style').val());$(img).attr('dir',$('#dir').val());$(img).css('float','');$(img).css('vertical-align','');v=$('#align').val();if(v=='left'||v=='right'){$(img).css('float',v);}else{$(img).css('vertical-align',v);}
$.each(['width','color','style'],function(){if($('#border').is(':checked')){v=$('#border_'+this).val();}else{v='';}
if(this=='width'&&/[^a-z]/i.test(v)){v+='px';}
$(img).css('border-'+this,v);});$.each(['top','right','bottom','left'],function(){v=$('#margin_'+this).val();$(img).css('margin-'+this,/[^a-z]/i.test(v)?v+'px':v);});$('#style').val(ed.dom.serializeStyle(ed.dom.parseStyle($(img).attr('style'))));}};tinyMCEPopup.requireLangPack();tinyMCEPopup.onInit.add(IframeDialog.init,IframeDialog);