<?php
	function up($dict, $db_prefix)
	{
		SilkDatabase::alter_column('pages', 'extra_params XL, metadata XL');
		SilkDatabase::alter_column('content', 'extra_params XL');
	}
	
	function down($dict, $db_prefix)
	{
		SilkDatabase::alter_column('pages', 'extra_params X, metadata X');
		SilkDatabase::alter_column('content', 'extra_params X');
	}
?>