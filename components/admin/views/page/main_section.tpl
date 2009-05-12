{form}

	<div id="module_page_tabs">
		<ul>
			<li><a href="#info"><span>Information</span></a></li>
			<li><a href="#edit"><span>Content</span></a></li>
			<li><a href="#attributes"><span>Attributes</span></a></li>
			<li><a href="#metadata"><span>Metadata</span></a></li>
			<li><a href="#preview"><span>Preview</span></a></li>
		</ul>
		<div id="info">
			{render_partial template="info.tpl"}
		</div>
		<div id="edit">
			{render_partial template="content.tpl"}
		</div>
		<div id="attributes">
		</div>
		<div id="metadata">
		</div>
		<div id="preview">
		</div>
	</div>
	<br />

	{submit value="Save"} {submit name="cancel" value="Cancel"} {submit value="Apply"}

{/form}