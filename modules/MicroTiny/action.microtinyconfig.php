<?php
if (!isset($gCms)) exit;
header("Content-type:text/javascript; charset=utf-8");

// Adapted from http://www.php.net/manual/en/function.session-id.php#54084
// session_id() returns an empty string if there is no current session, sotest if a session already exists before calling session_start() to prevent error notices:
if(session_id() == "") {
  session_start();
}

$frontend=false;
if (isset($params["frontend"]) && $params["frontend"]=="yes") {
  $frontend=true;
  echo "hi";
}

$config=& $gCms->GetConfig();

$templateid="";
if (isset($params["templateid"])) $templateid=$params["templateid"];

$languageid="";
if (isset($params["languageid"])) $languageid=$params["languageid"];

$microtiny = CmsModuleLoader::get_module_class('MicroTiny');
$configcontent = $microtiny->GenerateConfig($frontend, $templateid, $languageid);
echo $configcontent;

die();
?>