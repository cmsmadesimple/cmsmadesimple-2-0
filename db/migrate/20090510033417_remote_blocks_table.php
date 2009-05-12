<?php
	function up($dict, $db_prefix)
	{
		SilkDatabase::drop_table('blocks');
	}
	
	function down($dict, $db_prefix)
	{
		SilkDatabase::create_table('blocks',"
			id I KEY AUTO,
			content_id I,
			page_id I,
			name C(100),
			lang C(50),
			create_date T,
			modified_date T,
			extra_params X
		");
		SilkDatabase::create_index('blocks', 'page_and_name', 'page_id,name');
		SilkDatabase::create_index('blocks', 'page_and_name_and_lang', 'page_id,name,lang');
	}
?>