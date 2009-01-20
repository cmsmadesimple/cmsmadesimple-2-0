<div>
	{link text="Add Stylesheet" controller="stylesheet" action="add"}
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
	{foreach from=$stylesheets item='stylesheet'}
		<tr>
			<td>{$stylesheet.name}</td>
			<td>{if $stylesheet.active}True{else}False{/if}</td>
			<td>{link text='Edit' controller='stylesheet' action='edit' id=$stylesheet.id}</td>
			<td>{link text='Delete' controller='stylesheet' action='delete' id=$stylesheet.id confirm_text='Are you sure you want to delete?'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>

<div>
	{link text="Add Stylesheet" controller="stylesheet" action="add"}
</div>