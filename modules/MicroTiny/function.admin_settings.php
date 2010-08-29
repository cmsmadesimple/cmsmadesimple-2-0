<?php
if (!$this->VisibleToAdminUser()) {
	$this->ShowErrors($this->Lang("accessdenied"));
	return;
}
$usestaticconfig = $this->GetPreference('usestaticconfig',"0");
$allowimages = $this->GetPreference('allowimages',"0");
$css_styles = $this->GetPreference('css_styles',"");

$this->smarty->assign('css_styles_text', $this->Lang('css_styles_text'));
$this->smarty->assign('css_styles_help', $this->Lang('css_styles_help'));
$this->smarty->assign('css_styles_help2', $this->Lang('css_styles_help2'));
$this->smarty->assign('css_styles_input', $this->CreateTextArea(false,$id, $css_styles,'css_styles',"pagesmalltextarea","","","",'40','5'));
$this->smarty->assign('usestaticconfig_text', $this->Lang('usestaticconfig_text'));
$this->smarty->assign('usestaticconfig_help', $this->Lang('usestaticconfig_help'));

$this->smarty->assign('usestaticconfig_input', $this->CreateInputCheckbox($id, 'usestaticconfig', "1", $usestaticconfig ));


$this->smarty->assign('allowimages_text', $this->Lang('allowimages_text'));
$this->smarty->assign('allowimages_help', $this->Lang('allowimages_help'));
$this->smarty->assign('allowimages_input', $this->CreateInputCheckbox($id, 'allowimages', "1", $allowimages ));


$this->smarty->assign('startform', $this->CreateFormStart($id, 'savesettings', $returnid));
$this->smarty->assign('endform', $this->CreateFormEnd());


$this->smarty->assign('submit', $this->CreateInputSubmit($id, "savesettings", $this->Lang("savesettings")));
echo $this->ProcessTemplate('settings.tpl');

?>

