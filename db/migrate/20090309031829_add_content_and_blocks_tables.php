<?php
	function up($dict, $db_prefix)
	{
		SilkDatabase::create_table('content',"
			id I KEY AUTO,
			type C(50),
			lang C(50),
			unique_name C(255),
			create_date T,
			modified_date T,
			extra_params X
		");
		SilkDatabase::create_index('content', 'type', 'type');
		SilkDatabase::create_index('content', 'type_and_lang', 'type,lang');
		SilkDatabase::create_index('content', 'unique_name', 'unique_name');
		
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
	
	function down($dict, $db_prefix)
	{
		SilkDatabase::drop_table('blocks');
		SilkDatabase::drop_table('content');
	}
?>