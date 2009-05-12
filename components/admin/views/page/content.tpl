{foreach from=$page->template->get_page_blocks() key='name' item='block'}
	<h3>{$name}</h3>
	<h4>{$block.type}</h4>
{/foreach}
