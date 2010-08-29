<?php
$CMS_ADMIN_PAGE=1;
$path = dirname(dirname(dirname(__FILE__)));
require_once($path . DIRECTORY_SEPARATOR . 'include.php');
$urlext='?'.CMS_SECURE_PARAM_NAME.'='.$_SESSION[CMS_USER_KEY];

check_login(); $userid = get_userid();

$config=&$gCms->GetConfig();

$modules =&$gCms->modules;
$tiny=$modules["MicroTiny"]['object'];
$tiny->curlang=get_preference($userid, 'default_cms_language');
$filepickerstyle=$tiny->GetPreference("filepickerstyle","both");
$tiny->smarty->assign("filepickerstyle",$filepickerstyle);

$tiny->smarty->assign("rooturl",$config["root_url"]);
$tiny->smarty->assign("admindir",$config["admin_dir"]);
$tiny->smarty->assign("filepickertitle",$tiny->Lang("filepickertitle"));
$tiny->smarty->assign("youareintext",$tiny->Lang("youareintext"));


$rootpath=""; $rooturl="";
if ($_GET["type"]=="image") {
	$rootpath=$config["image_uploads_path"];
	$rooturl=$config["image_uploads_url"];
	$tiny->smarty->assign("isimage","1");
} else {
	$rootpath=$config["uploads_path"];
	$rooturl=$config["uploads_url"];
	$tiny->smarty->assign("isimage","0");
}
if (strtolower(substr($rooturl,0,5))=="https") {
  $rooturl=substr($rooturl,8); //remove https:/
} else {
	$rooturl=substr($rooturl,7); //remove http:/
}
$rooturl=substr($rooturl,strpos($rooturl,"/")); //Remove domain

$subdir="";
if (isset($_GET["subdir"])) $subdir=trim($_GET["subdir"]);
$subdir=str_replace(".","",$subdir); //avoid hacking attempts

if ($subdir!="" && $subdir[0]=="/") $subdir=substr($subdir,1);

$thisdir=$rootpath.'/';
if ($subdir!="") $thisdir.=$subdir."/";
$thisurl=$rooturl.'/';
if ($subdir!="") $thisurl.=$subdir."/";

$tiny->smarty->assign("subdir",$subdir);

function sortfiles($file1,$file2) {
	if ($file1["isdir"] && !$file2["isdir"]) return -1;
	if (!$file1["isdir"] && $file2["isdir"]) return 1;
	return strnatcasecmp($file1["name"],$file2["name"]);
}

$fmmodule=$tiny->GetModuleInstance("FileManager");

$files = Array();

$d = dir($thisdir);
while ($entry = $d->read()) {
	if ($entry[0]==".") continue;
	if (substr($entry,0,6)=="thumb_") {
    if ($tiny->GetPreference("showthumbnailfiles")!="1") {
      continue;
    }
  }

	$file=array();
	$file["name"]=$entry;
	$file["isimage"]="0";
	$file["fullpath"]=$thisdir.$entry;
	$file["fullurl"]=$thisurl.$entry;
	$file["ext"]=strtolower(substr($entry,strrpos($entry,".")));

	$file["isdir"]=is_dir($file["fullpath"]);


	if (!$file["isdir"]) {
	  if ($_GET["type"]=="image") {
	    if ($file["ext"]!=".jpeg" && $file["ext"]!=".jpg" && $file["ext"]!=".gif" && $file["ext"]!=".png") {
	      continue;
	    } 
	    // 		}		
	    // 		if ($file["ext"]==".jpg" || $file["ext"]==".gif" || $file["ext"]==".png") {
	    else {
	      $file["isimage"]="1";
	    }
		
	    if ($filepickerstyle!="filename") {
        if ($tiny->GetPreference("showthumbnailfiles")=="1") {
	        $file["thumbnail"]=$tiny->GetThumbnailFile(str_replace("thumb_","",$entry),$thisdir,$thisurl);
        } else {
          $file["thumbnail"]=$tiny->GetThumbnailFile($entry,$thisdir,$thisurl);
        }
	    }
	    $imgsize=@getimagesize($file["fullpath"]);
	    if ($imgsize) {
	      $file["dimensions"]=$imgsize[0]."x".$imgsize[1];
	    } else {
	      $file["dimensions"]="&nbsp;";
	    }
	  }
	}

  if ($fmmodule) {
    $file["fileicon"]=$fmmodule->GetFileIcon($file["ext"],$file["isdir"]);    
  }
	if (!$file["isdir"]) {
		$info=@stat($file["fullpath"]);
    if ($info) {
		  $file["size"]=$info["size"];
    }		
	}
	$files[]=$file;
}
$d->close();
usort($files,"sortfiles");

$showfiles=array();

if ($subdir!="") {
	$onerow = new stdClass();
	$onerow->isdir="1";
	$onerow->thumbnail="";
	$onerow->dimensions="";
	$onerow->size="";
	$newsubdir=dirname($subdir);
	$onerow->namelink="<a href='".$config["root_url"]."/modules/MicroTiny/filepicker.php".$urlext."&amp;type=".$_GET["type"]."&amp;subdir=".$newsubdir."'>[..]</a>\n";
	array_push($showfiles, $onerow);
}

$filecount=0;
$dircount=0;
foreach($files as $file) {
	$onerow = new stdClass();
	$onerow->name=$file["name"];

  $onerow->name=$file["name"];
  $onerow->fileicon=$file["fileicon"];
	if ($file["isdir"]) {
		$onerow->isdir="1";		
		$onerow->namelink="<a href='".$config["root_url"]."/modules/MicroTiny/filepicker.php".$urlext."&amp;type=".$_GET["type"]."&amp;subdir=".$subdir."/".$file["name"]."'>[".$file["name"]."]</a>\n";
		$dircount++;
	} else {
		$onerow->isdir="0";
		$onerow->isimage=$file["isimage"];
		if (isset($file["thumbnail"])) $onerow->thumbnail=$file["thumbnail"];
		$onerow->fullurl=$file["fullurl"];
		if (isset($file["dimensions"])) {
			$onerow->dimensions=$file["dimensions"];
		} else {
			$onerow->dimensions="&nbsp;";
		}    
		$onerow->size=number_format($file["size"],0,"",$tiny->Lang("thousanddelimiter"));
		$filecount++;
	}
	array_push($showfiles, $onerow);
}
$tiny->smarty->assign('dircount', $dircount);
$tiny->smarty->assign('filecount', $filecount);

$tiny->smarty->assign('sizetext', $tiny->Lang("size"));
$tiny->smarty->assign('dimensionstext', $tiny->Lang("dimensions"));

$tiny->smarty->assign_by_ref('files', $showfiles);
$tiny->smarty->assign('filescount', count($showfiles));



echo $tiny->ProcessTemplate('filepicker.tpl');

?>