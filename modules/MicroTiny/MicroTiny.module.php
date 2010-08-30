<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
#CMS - CMS Made Simple
#(c)2004-2008 by Ted Kulp (ted@cmsmadesimple.org)
#This project's homepage is: http://cmsmadesimple.org
#
#This program is free software; you can redistribute it and/or modify
#it under the terms of the GNU General Public License as published by
#the Free Software Foundation; either version 2 of the License, or
#(at your option) any later version.
#
#This program is distributed in the hope that it will be useful,
#but WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.
#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

class MicroTiny extends CmsModuleBase implements CmsModuleWysiwyg
{
	var $templateid;

	function wysiwyg_textarea($name = 'textarea', $columns = '80', $rows = '15', $encoding = '', $content = '', $stylesheet = '', $addtext = '')
	{
		if (!$this->is_active && isset($_SESSION["microtiny_live_textareas"])) {
			$_SESSION["microtiny_live_textareas"]="";
			unset($_SESSION["microtiny_live_textareas"]);
		}

		$this->is_active = true;
		global $gCms;
		$variables = &$gCms->variables;

		if ($stylesheet != '') {
			$_SESSION["microtiny_live_templateid"]=substr($stylesheet, strpos($stylesheet,"=")+1);
		} else {
			$tplops=$gCms->GetTemplateOperations();
			$templateid=$tplops->LoadDefaultTemplate();
			$this->templateid=$templateid->id;
		}
		if (!isset($_SESSION["microtiny_live_textareas"])) {
			$_SESSION["microtiny_live_textareas"]=$name;
		} else {
			$_SESSION["microtiny_live_textareas"].=",".$name;
		}
		$result='<textarea id="'.$name.'" name="'.$name.'" cols="'.$columns.'" rows="'.($rows+5).'" '.$addtext.'>'.cms_htmlentities($content,ENT_NOQUOTES,get_encoding($encoding)).'</textarea>';
		return $result;
	}

	function wysiwyg_page_form_submit()
	{
		return 'tinyMCE.triggerSave();';
	}
	
	function wysiwyg_generate_body()
	{
		return '';
	}
	
	function wysiwyg_page_form()
	{
		return '';
	}

	function wysiwyg_generate_header($html_result = '')
	{
		global $gCms;
		$frontend = '';
		$languageid = $this->GetLanguageId($frontend);

		if ($this->Preference->get("usestaticconfig")=="1") {
			if (!$this->IsTempWritable()) {
				//echo $this->ShowErrors($this->Lang("usestaticconfigwarning"));
			} else {
				if ($this->Preference->get("usestaticconfig")) {
					$this->SaveStaticConfig($frontend,$this->templateid,$languageid);
				}
			}
		}

		if ($this->is_active) {
			$output='
				<script type="text/javascript" src="' . CmsRequest::get_calculated_url_base(true) . '/modules/MicroTiny/tinymce/tiny_mce.js"></script>';
			$configurl="";
			if ($this->Preference->get("usestaticconfig")!="1") {
				$params=array("templateid"=>$this->templateid,"languageid"=>$languageid);
				if ($frontend) $params["frontend"]="yes";
				$configurl = $this->Url->link(array('id' => 'm1_', 'action' => 'microtinyconfig', 'params' => $params, 'only_href' => true)); /*"m1_","microtinyconfig","","",$params,"",true*/
				$configurl.="&amp;showtemplate=false";
			} else {
				$configurl = CmsRequest::get_calculated_url_base(true) . '/tmp/microtinyconfig.js';
			}

			$output.='
				<script type="text/javascript" src="' . $configurl . '"></script>
				';
		} else {
			$output="<!-- MicroTiny Session vars empty -->";
		}
		return $output;
	}

	function GetHelp($lang='en_US') {
		return $this->Lang('help');
	}

	function GetChangeLog()	{
		return $this->ProcessTemplate('changelog.tpl');
	}

	function GetLanguageId() {
	  global $gCms;
		$mylang=$gCms->userprefs["default_cms_language"];
		if ($mylang=="") return "en"; //Lang setting "No default selected"
		$mylang=substr($mylang,0,2);
		switch ($mylang) {			
			case "tw" : return strtolower($gCms->userprefs["default_cms_language"]);
			//case "pt" : return "pt_br";// case pt is pt, so, dont need pt_br
			default : return $mylang;
		}
	}

  function VisibleToAdminUser() {
		return $this->CheckPermission('Modify Site Preferences');
	}

	function SaveStaticConfig($frontend=false, $templateid="", $languageid="") {    
	  $config=$this->GetConfig();
		$configcontent=$this->GenerateConfig($frontend, $templateid, $languageid);
		$filename=$this->Slashes($config["root_path"]."/tmp/microtinyconfig.js");
		return file_put_contents($filename,$configcontent);
	}

  function GetThumbnailFile($file,$path,$url) {
		$image="";
		$imagepath=$this->Slashes($path."/thumb_".$file);
		$imageurl=$this->Slashes($url."/thumb_".$file);
		//echo "<pre>".$imageurl."</pre><br/>";
		if (!file_exists($imagepath)) {
			//echo $imagepath;
			$image="";//$this->GetFileIcon($file["ext"],$file["dir"]);
		} else {
			$image="<img src='".$imageurl."' alt='".$file."' title='".$file."' />";
		}
		return $image;
	}

	function Slashes($url) {
		$result=str_replace("\\","/",$url);
		return $result;
	}
	
	function GetConfig() {
		return cms_config();
	}

  function IsTempWritable() {
		$config=$this->GetConfig();
		$filename=$this->Slashes($config["root_path"]."/tmp/microtinyconfig.js");
		//$file=@fopen($filename,"w"));
		//if (!$file) return false;
		//echo $filename;
		if (!@touch($filename)) return false;
		return true;
	}

  function AddEntry($menu,$entry) {
	$config=$this->GetConfig();

	$link="";
	$result="";
	//{format : 'text'}
  $menutext=$entry->Hierarchy()." ".$entry->MenuText();
  if (strlen($menutext)>30) {
    $menutext=htmlspecialchars(substr($menutext,0,30),ENT_QUOTES)." &#133;";
  } else {
    $menutext=htmlspecialchars($menutext,ENT_QUOTES);
  }
	$result.="
					".$menu.".add({title : '".$menutext."', onclick : function() {
						var sel=tinyMCE.activeEditor.selection.getContent();
						if (sel=='') sel='".htmlspecialchars($entry->MenuText(),ENT_QUOTES)."';";

	if ($this->Preference->get("cmslinkerstyle","selflink")=="a") {
		if (($config['url_rewriting'] != 'none')
	     /*&& $config['use_hierarchy'] == true*/) {
      if ($config['url_rewriting'] == 'mod_rewrite') {
  		  $link = $config['root_url']."/".$entry->HierarchyPath().$config['page_extension'];
	  	} else {
		  	$link= $config['root_url']."/index.php/".$entry->HierarchyPath().$config['page_extension'];
		  }
	  }	else {
	    //if ($tiny->GetPreference("cmslinkerstyle","selflink")=="a") {
	    $link="index.php?".$config['query_var']."=".$entry->Alias();
	    //}
	  }
		$result.="
						tinyMCE.activeEditor.execCommand('mceInsertContent', false, '<a href=\"".$link."\">'+sel+'</a>');";
	} else {
		$result.="
						tinyMCE.activeEditor.execCommand('mceInsertContent', false, \"{cms_selflink page='".$entry->Alias()."' text='\"+sel+\"'}\");";
	}
	$result.="
					}});
";
	return $result;
}

function AddSub($menu,$entry,&$result) {
  $menutext=$entry->Hierarchy()." ".$entry->MenuText();
  if (strlen($menutext)>30) {
    $menutext=htmlspecialchars(substr($menutext,0,30),ENT_QUOTES)." &#133;";
  } else {
    $menutext=htmlspecialchars($menutext,ENT_QUOTES);
  }
	$newmenu=$menu."m";
	$result.="					var ".$newmenu." = ".$menu.".addMenu({title : '".$menutext."'});
";
	if ($entry->Type()!="sectionheader") {
	  $result.=$this->AddEntry($newmenu,$entry);
	  $result.="					".$newmenu.".addSeparator();
";
	}
	return $newmenu;
}

	function GetCMSLinker() {
  
  $result="";
  $config=$this->GetConfig();
	$result.="

//Creates a new plugin class and a custom listbox
tinymce.create('tinymce.plugins.CMSLinkerPlugin', {
	createControl: function(n, cm) {
		switch (n) {
			case 'cmslinker':
				var c = cm.createMenuButton('cmslinker', {
					title : '".$this->Lang("cmsmslinker")."',
					image : '".$config["root_url"]."/modules/MicroTiny/images/cmsmslink.gif',
					icons : false
				});

				c.onRenderMenu.add(function(c, m) {
";

global $gCms;
$content_ops =& $gCms->GetContentOperations();
$content_array=$content_ops->GetAllContent();

$level=0;
$menu="m";




foreach ($content_array as $one) {
	if ($one->Active()!=1) continue;
	if ($one->FriendlyName() == 'Separator') {
		continue;
	}
		//print_r($one);
	$thislevel=substr_count($one->Hierarchy(),".");
	//echo $thislevel.":".$level;
  if ($thislevel<$level) {
  	$menu=substr($menu,($level-$thislevel));
			$level-=($level-$thislevel);

		}
	//if ($thislevel==$level) {
		if ($one->mChildCount>0) {
			$menu=$this->AddSub($menu,$one,$result);
			$level++;
		} else {
			$result.=$this->AddEntry($menu,$one);
		}
}

$result.="
				});

				// Return the new menu button instance
				return c;
		}

		return null;
	}
});

// Register plugin with a short name
tinymce.PluginManager.add('cmslinker', tinymce.plugins.CMSLinkerPlugin);

";
return $result;
	}

	function GenerateConfig($frontend=false, $templateid="", $languageid="en") {
		$result="";		
		

	$smarty = cms_smarty();
    $config=$this->GetConfig();
    $linker="";
    if ($frontend) {      
      $smarty->assign("isfrontend","true");
    } else {
      $smarty->assign("isfrontend","false");
      $result.=$this->GetCMSLinker();
      $linker="cmslinker,";
    }

    $textareas="";
    if (isset($_SESSION["microtiny_live_textareas"])) $textareas=$_SESSION["microtiny_live_textareas"];
		$smarty->assign("textareas",$textareas);
//function CreateLink($id, $action, $returnid='', $contents='', $params=array(), $warn_message='', $onlyhref=false, $inline=false, $addttext='', $targetcontentonly=false, $prettyurl='')
		//$smarty->assign("css",$config['root_url']."/modules/MicroTiny/stylesheet.php?templateid=".$templateid."&amp;mediatype=screen&amp;bogus=".time());
		/*$cssurl=$this->CreateURL('m1_',
					 'stylesheet',
					 '',
					 array("templateid"=>$templateid,"mediatype"=>"screen"));*/
	/*
    $cssurl=$this->CreateLink('m1_',
					 'stylesheet',
					 '','',
					 array("templateid"=>$templateid,"mediatype"=>"screen"),"",true);*/
	$cssurl = $this->Url->link(array('id' => 'm1_', 'action' => 'stylesheet', 'params' => array("templateid"=>$templateid, "mediatype"=>"screen"), 'only_href' => true));
    $cssurl .= "&amp;showtemplate=false";
    $cssurl = str_replace('amp;','',$cssurl);
    $smarty->assign("css",$cssurl);

		$smarty->assign("rooturl",$config["root_url"]);		

    $urlext="";
    if (isset($_SESSION[CMS_USER_KEY])) {
      $urlext=CMS_SECURE_PARAM_NAME.'='.$_SESSION[CMS_USER_KEY];
    }
    $smarty->assign("urlext",$urlext);
    //,pasteword,,|,undo,redo
    $image="";
    if ($this->Preference->get("allowimages",0)==1) {
      $image=",image,|";
    }
    $toolbar="bold,italic,underline,|,cut,copy,paste,pastetext,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,".$linker."link,unlink,|".$image.",formatselect"; //,separator,styleselect
 		$css_styles = $this->Preference->get('css_styles');
		$css_styles =str_replace("\n",",",$css_styles);
		$css_styles =str_replace("\r",",",$css_styles);

    if ($css_styles!="") {
      $toolbar.=",separator,styleselect";
      $smarty->assign("css_styles",$css_styles);
    }
    $smarty->assign("toolbar",$toolbar);				

    $smarty->assign("language",$languageid);
		
		$smarty->assign("filepickertitle",$this->Lang("filepickertitle"));
		
		$result.=$this->Template->process('microtinyconfig.tpl');
		return $result;

	}
	
}

?>
