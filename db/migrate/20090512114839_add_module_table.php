<?php
	function up($dict, $db_prefix)
	{
		SilkDatabase::create_table('modules', 'id I KEY AUTO, module_name C(50), version C(10), active I1 default 1, create_date T, modified_date T');
	}
	
	function down($dict, $db_prefix)
	{
		SilkDatabase::drop_table('modules');
	}
?>