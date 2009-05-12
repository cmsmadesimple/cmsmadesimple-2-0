{assign var='page' value=$tree->get_root_node()}

<h2>Content</h2>

<div style="width: 1000px; margin-top: 15px;">

<div id="content_tree" style="width: 240px; float: left; border-right: 1px solid black">
<ul>
	<li id="node_1" class="open">
		<a href="#">ROOT</a>
		{if $page->has_children()}
			{assign var='subpages' value=$page->get_children()}
			{render_partial template='branch.tpl'}
		{/if}
	</li>
</ul>
</div>

<div id="main_section" style="float: right; min-width: 750px; text-align: left;">
	
</div>

</div>

<br style="clear: both;" />

{literal}
<script type="text/javascript">
//<![CDATA[
	$('#content_tree').tree(
		{
			'rules' :
			{
				'draggable' : 'all'
			},
			'callback' :
			{
				'onselect' : function(node, tree_obj)
				{
					if (node.id != 'node_1')
					{
						silk_ajax_call('{/literal}{php}echo SilkResponse::create_url(array('controller' => 'page', 'action' => 'main_content')){/php}{literal}', [{name:'node_id', value:node.id}]);
					}
					else
					{
						clear_main_content();
					}
				},
				'ondeselect' : function(node, tree_obj)
				{
					clear_main_content();
				}
			}
		}
	);
	
	function clear_main_content()
	{
		$('#main_section').html('');
	}
	
	function reset_main_content()
	{
		$('#module_page_tabs > ul').tabs();
		setup_form();
		$('#unique_alias').delayedObserver(function()
		{
			silk_ajax_call('{/literal}{php}echo SilkResponse::create_url(array('controller' => 'page', 'action' => 'check_unique_alias')){/php}{literal}', [{name:'alias', value:$('#unique_alias').val()}, {name:'page_id', value:$('#page_id').html()}]);
		}, 0.5);
		$('#alias').delayedObserver(function()
		{
			silk_ajax_call('{/literal}{php}echo SilkResponse::create_url(array('controller' => 'page', 'action' => 'check_alias')){/php}{literal}', [{name:'alias', value:$('#alias').val()}, {name:'page_id', value:$('#page_id').html()}]);
		}, 0.5);
	}
	
	$(document).ready(function()
	{
		reset_main_content();
	});
//]]></script>
{/literal}
