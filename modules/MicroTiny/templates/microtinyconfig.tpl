{literal} tinyMCE.init({ {/literal}
  {* Setup *}
  mode : "exact",
  elements : "{$textareas}",
  body_class : "CMSMSBody",
  content_css : "{$css}",

  {* //Performance *}
  entity_encoding : "raw",
  button_tile_map : true, 

	{* //Visual *}	
  theme : "advanced",
  skin : "o2k7",
  skin_variant : "black",
  theme_advanced_toolbar_location : "top",
  theme_advanced_toolbar_align : "left",
  visual : true,
	      
  accessibility_warnings : false,
      			
  fix_list_elements : true,
  verify_html : true,
  verify_css_classes : false,
  
  plugins : "paste,inlinepopups,cmslinker",
  
  paste_auto_cleanup_on_paste : true,
  paste_remove_spans : true,
  paste_remove_styles : true,
  theme_advanced_buttons1 : "{$toolbar}",
  theme_advanced_buttons2 : "",
  theme_advanced_buttons3 : "",
  
  theme_advanced_blockformats : "h1,h2,h3,h4,h5,h6,blockquote,code",
  document_base_url : "{$rooturl}/",

  relative_urls : true,
  remove_script_host : true,
  language: "{$language}",
  dialog_type: "modal",
  apply_source_formatting : true  
	 
{* From here statements should start with , as it's not certaain anymore is coming *}
{if $css_styles!=''}
  ,theme_advanced_styles : '{$css_styles}'
{/if}
{if $isfrontend=='false'}
  ,file_browser_callback : 'CMSMSFilePicker'
{/if}
  {literal}
});
  {/literal}
	
  {if $isfrontend=='false'}
  {literal}
function CMSMSFilePicker (field_name, url, type, win) {
  {/literal}   
  var cmsURL = "{$rooturl}/modules/MicroTiny/filepicker.php?{$urlext}&type="+type;
  {literal}
  tinyMCE.activeEditor.windowManager.open({
  {/literal}
    file : cmsURL,
    title : '{$filepickertitle}',
    width : '700',
    height : '400',
    resizable : "yes",
    scrollbars : "yes",
    inline : "yes",  {* This parameter only has an effect if you use the inlinepopups plugin! *}
    close_previous : "no"
  {literal}
  }, {
    window : win,
    input : field_name
  });
  return false;
}
{/literal}
{/if}
