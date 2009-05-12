<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta name="Generator" content="CMS Made Simple - Copyright (C) 2004-2009 Ted Kulp. All rights reserved." />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title>{$sitename} - {$title}</title>

	<link rel="stylesheet" href="{$layout_root_url}/css/kevin_tabs.css" type="text/css" />
	<link rel="stylesheet" href="{$layout_root_url}/css/style.css" type="text/css" />
	<!--[if IE]>
		<script type="text/javascript" src="{$layout_root_url}/includes/ie7-standard-p.js"></script>
	<![endif]-->
	<!-- <script type="text/javascript" src="{$layout_root_url}/includes/standard.js"></script> -->

	<!-- jQuery and tabs -->
	<script type="text/javascript" src="{$silk_lib_root_url}/jquery/jquery.js"></script>
	<script type="text/javascript" src="{$silk_lib_root_url}/jquery/jquery.color.js"></script>
	<script type="text/javascript" src="{$silk_lib_root_url}/jquery/jquery.silk.js"></script>
	<script type="text/javascript" src="{$silk_lib_root_url}/jquery/ui/ui.tabs.js"></script>

	<!-- Tree stuff -->
	<link rel="stylesheet" type="text/css" href="{$cmsms_lib_root_url}/js/jstree/source/tree_component.css" />
	<script type="text/javascript" src="{$cmsms_lib_root_url}/js/jstree/lib/css.js"></script>
	<script type="text/javascript" src="{$cmsms_lib_root_url}/js/jstree/source/tree_component.js"></script>
	<script type="text/javascript" src="{$cmsms_lib_root_url}/js/jstree/lib/jquery.metadata.js"></script>
	<script type="text/javascript" src="{$cmsms_lib_root_url}/js/jstree/lib/jquery.cookie.js"></script>
	
	{literal}
	<script type="text/javascript">
	//<![CDATA[
	// form handling stuff
	$(document).ready(function()
	{
		setup_form();
	});
		
	function setup_form()
	{
		// disable all buttons
		$('button').addClass("disabled").attr("disabled", true);
		
		// but enable cancel
		$('button[@name="cancel"]').removeClass("disabled").attr("disabled", false);
		
		// assign event for every input
		$('form :input').one("change", function()
		{
			// on change call enable form
			enableForm(this.form);
			// mark in title
			if(document.title[0] != "*")
			{
				document.title = "*"+document.title;
			}
		});
		
		// fill the default help text
		var help = $('#default_helptext').html();	
		$('#HelpContent').html(help);

		// assign an event for every form row
		$("div[id*='formrow']").bind("click", function()
		{
			var help = $(this).children("input[id*='help_']").val();
			$('#HelpContent').html(help);
		});
	}

	function enableForm(form) {
		var input = $('button', form);
		input.attr('disabled', false).removeClass('disabled');
	}

	//]]></script>
	{/literal}
	
	{$headtext}
	
	<base href="{$root_url}" />

</head>

<body>
	
	<div id="clean-container">
	
		{include file="$layout_root_path/topmenu.tpl"}

		<div id="MainContent">
		
			{*
			<div class="pagemcontainer">
				<p class="pagemessage" id="pagemessage">
					{php}echo SilkFlash::get_instance()->get('std'){/php}
				</p>
			</div>
			*}
			
			<div class="pagecontainer" id="pagecontent">
				{$content}
			</div>
			
			<div class="clearb"></div>
			<div id="HelpContent"></div>
		
		</div><!-- end MainContent -->

		<div id="footer">
			<a rel="external" href="http://www.cmsmadesimple.org"><b>CMS Made Simple</b></a> {$cms_version} "{$cms_versionname}"<br /><b>CMS Made Simple</b> is free software released under the General Public Licence.
		</div>
		
	</div><!--end clean-container-->

</body>
</html>
{php}echo SilkProfiler::get_instance()->report();{/php}