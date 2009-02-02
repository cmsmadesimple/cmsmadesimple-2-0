{foreach from=$templates item='template'}
<tr class="{cycle values='row1,row2'}">
	<td>{link text=$template.name controller='page_template' action='edit' id=$template.id}</td>
	<td class="pagepos">{if $template.default}{img src="images/icons/system/true.gif" alt="True" title="True"}{else}{img src="images/icons/system/false.gif" title="False" alt="False"}{/if}</td>
	<td class="pagepos">{if $template.active}{img src="images/icons/system/true.gif" alt="True" title="True"}{else}{img src="images/icons/system/false.gif" alt="False" title="False"}{/if}</td>
	<td class="icons_wide">{img_link src="images/icons/system/edit.gif" alt="Edit" title="Edit" controller='page_template' action='edit' id=$template.id}</td>
	<td class="icons_wide">{img_link src="images/icons/system/delete.gif" alt="Delete" title="Delete" controller='page_template' action='delete' id=$template.id confirm_text='Are you sure you want to delete?'}</td>
</tr>
{/foreach}