<?php
$lang["friendlyname"]="MicroTiny WYSIWYG editor";
$lang["help"]="
<strong>What does this module do?</strong>
<br/>
MicroTiny is a small version of the TinyMCE-editor, formerly the wysiwyg-default of CMS Made Simple.
This provides nothing more than the basics of editing, but is still a powerful tool
allowing easy changes to content pages.
<br/><br/>
<strong>What can go wrong?</strong>
<br/>
MicroTiny relies heavily on JavaScript so a newer browser is needed. IE7+, and almost all others should suffice, though.
One some servers the option 'Use static config' is needed to make the editor work. Try that if you see a plain editor-field
with no wysiwyg-buttons. But if it works for you without this option on, it's better to leave it off.";

$lang["example"]="MicroTiny example";
$lang["settings"]="Settings";

$lang["youareintext"]="You are in";
$lang["dimensions"]="WxH";
$lang["size"]="Size";
$lang["filepickertitle"]="File picker";
$lang["cmsmslinker"]="CMSMS Linker";
$lang["tmpnotwritable"]="The configuration could not be written to the tmp-dir! Please fix that!";
$lang["css_styles_text"]="CSS Styles";
$lang["css_styles_help"]="CSS-stylenames specified here is added to a dropdownbox in the editor. Leaving the input field empty will keep the dropdown-box hidden (default behavior).";

$lang["css_styles_help2"]="The styles can either be just the class name, or a classname with a new name to show.
Must be sepereated by either commas or newlines.
<br/>Example: mystyle1, My style name=mystyle
<br/>Result: a dropdown containing 2 entries, 'mystyle1' and 'My stylename' resulting in the insertion of mystyl1, and mystyle2 respectively.
<br/>Note: No checking for the actual existence of the stylenames are done. They are used blindly.";

$lang["usestaticconfig_text"]="Use static config";
$lang["usestaticconfig_help"]="This generates a static configuration file instead of the dynamic one. Works better on some servers (for instance when running PHP as CGI)";

$lang["allowimages_text"]="Allow images";
$lang["allowimages_help"]="This enables an image button on the toolbar, allowing inserting an image into the content";

$lang["settingssaved"]="Settings saved";
$lang["savesettings"]="Save settings";
?>
