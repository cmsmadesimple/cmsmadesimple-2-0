{foreach from=$subpages item='page'}
<ul>
	<li id="node_{$page->id}">
		<a href="#">{$page->alias}</a>
		{if $page->has_children()}
			{assign var='subpages' value=$page->get_children()}
			{render_partial template='branch.tpl'}
		{/if}
	</li>
</ul>
{/foreach}