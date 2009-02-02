<div class="pageoverflow">
	<div class="pageheader">Stylesheets
		<span class="helptext">
			<a href="http://wiki.cmsmadesimple.org/index.php/User_Handbook/Admin_Panel/Layout/Stylesheets" rel="external">
				<img src="{$layout_root_url}/images/icons/system/info-external.gif" class="systemicon" alt="Help" title="Help" />
			</a>
			<a href="http://wiki.cmsmadesimple.org/index.php/User_Handbook/Admin_Panel/Layout/Stylesheets" rel="external">Help</a> (new window)
		</span>
	</div>
</div>

<table cellspacing="0" class="pagetable">
	<thead>
		<tr>
			<th class="pagew50">Template</th>
			<th class="pagepos">Active</th>
			<th class="pageicon">&nbsp;</th>
			<th class="pageicon">&nbsp;</th>
		</tr>
	</thead>
	<tbody id="tablebody">
		{render_partial template='indextablebody.tpl'}
	</tbody>
</table>

<div class="pageoptions">
	<p class="pageoptions">
		<span style="float: left;">
			<a href="{link only_href='true' controller='stylesheet' action='add'}"><img src="{$layout_root_url}/images/icons/system/newobject.gif" class="systemicon" alt="Add Stylesheet" title="Add Stylesheet" /></a>
			{link html_class="pageoptions" text="Add Stylesheet" controller="stylesheet" action="add"}
		</span>
	</p>
</div>

<br />
