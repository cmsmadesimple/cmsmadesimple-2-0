{foreach from=$stylesheets item='stylesheet'}
<tr class="{cycle values='row1,row2'}">
	<td>{link text=$stylesheet.name controller='stylesheet' action='edit' id=$stylesheet.id}</td>
	<td class="pagepos">{if $stylesheet.active}{img src="images/icons/system/true.gif" alt="True" title="True"}{else}{img src="images/icons/system/false.gif" alt="False" title="False"}{/if}</td>
	<td class="icons_wide">{img_link src="images/icons/system/edit.gif" alt="Edit" title="Edit" controller='stylesheet' action='edit' id=$stylesheet.id}</td>
	<td class="icons_wide">{img_link src="images/icons/system/delete.gif" alt="Delete" title="Delete" controller='stylesheet' action='delete' id=$stylesheet.id confirm_text='Are you sure you want to delete?'}</td>
</tr>
{/foreach}
