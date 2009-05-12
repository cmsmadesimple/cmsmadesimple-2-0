<?php
	function up($dict, $db_prefix)
	{
		SilkDatabase::create_table('templates', "
			id I KEY AUTO,
			template_name C(255),
			template_content XL,
			encoding C(25),
			active I1 default 1,
			default_template I1,
			create_date T,
			modified_date T
		");
		SilkDatabase::create_index('templates', 'template_name', 'template_name');
		
		SilkDatabase::create_table('stylesheets', "
			id I KEY AUTO,
			name C(255),
			value XL,
			active I1 default 1,
			media_type C(255),
			create_date T,
			modified_date T
		");
		SilkDatabase::create_index('stylesheets', 'name', 'name');
		
		SilkDatabase::create_table('stylesheet_template_assoc', "
			id I KEY AUTO,
			stylesheet_id I,
			template_id I,
			order_num I,
			create_date T,
			modified_date T
		");
		SilkDatabase::create_index('stylesheet_template_assoc', 'stylesheet_id', 'stylesheet_id');
		SilkDatabase::create_index('stylesheet_template_assoc', 'template_id', 'template_id');
		SilkDatabase::create_index('stylesheet_template_assoc', 'stylesheet_id_template_id', 'stylesheet_id,template_id');
		
		SilkDatabase::create_table('attributes', "
			id I KEY AUTO,
			module C(100),
			class_name C(100),
			object_id I,
			name C(255),
			content XL,
			language C(50),
			create_date T,
			modified_date T
		");
		SilkDatabase::create_index('attributes', 'object_and_class_and_module_and_lang', 'module,class_name,object_id,language');
		SilkDatabase::create_index('attributes', 'object_and_class_and_module_and_name_and_lang', 'module,class_name,object_id,name,language');
		SilkDatabase::create_table('pages', "
			id I KEY AUTO,
			type C(255),
			owner_id I,
			parent_id I,
			template_id I,
			lft I,
			rgt I,
			item_order I,
			hierarchy C(255),
			default_page I1,
			alias C(200),
			unique_alias C(255),
			hierarchy_path X,
			show_in_menu I1,
			active I1,
			cachable I1,
			metadata X,
			last_modified_by I,
			create_date T,
			modified_date T,
			extra_params X
		");
		SilkDatabase::create_index('pages', 'alias_and_active', 'alias,active');
		SilkDatabase::create_index('pages', 'default_page', 'default_page');
	}
	
	function down($dict, $db_prefix)
	{
		SilkDatabase::drop_index('pages', 'default_page');
		SilkDatabase::drop_index('pages', 'alias_and_active');
		SilkDatabase::drop_table('pages');
		SilkDatabase::drop_index('attributes', 'object_and_class_and_module_and_name_and_lang');
		SilkDatabase::drop_index('attributes', 'object_and_class_and_module_and_lang');
		SilkDatabase::drop_table('attributes');
		SilkDatabase::drop_index('stylesheet_template_assoc', 'stylesheet_id_template_id');
		SilkDatabase::drop_index('stylesheet_template_assoc', 'template_id');
		SilkDatabase::drop_index('stylesheet_template_assoc', 'stylesheet_id');
		SilkDatabase::drop_table('stylesheet_template_assoc');
		SilkDatabase::drop_index('stylesheets', 'name');
		SilkDatabase::drop_table('stylesheets');
		SilkDatabase::drop_index('templates', 'template_name');
		SilkDatabase::drop_table('templates');
	}
?>