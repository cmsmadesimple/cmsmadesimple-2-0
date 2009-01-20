<div>
	{link text="Add Template" controller="page_template" action="add"}
</div>

<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Active</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$templates item='template'}
		<tr>
			<td>{$template.name}</td>
			<td>{if $template.active}True{else}False{/if}</td>
			<td>{link text='Edit' controller='page_template' action='edit' id=$template.id}</td>
			<td>{link text='Delete' controller='page_template' action='delete' id=$template.id confirm_text='Are you sure you want to delete?'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>

<div>
	{link text="Add Template" controller="page_template" action="add"}
</div>