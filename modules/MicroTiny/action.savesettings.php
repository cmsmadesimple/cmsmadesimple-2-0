<?php
if (!$this->VisibleToAdminUser()) $this->Redirect($id,"defaultadmin",$returnid);

if (isset($params['usestaticconfig'])) $this->SetPreference('usestaticconfig', 1 ); else $this->SetPreference('usestaticconfig', 0 );
if (isset($params['allowimages'])) $this->SetPreference('allowimages', 1 ); else $this->SetPreference('allowimages', 0 );

if (isset($params['css_styles'])) $this->SetPreference('css_styles',$params['css_styles']);

$this->Redirect($id,"defaultadmin",$returnid,array("module_message"=>$this->Lang("settingssaved"),"tab"=>$params["tab"]));
?>
