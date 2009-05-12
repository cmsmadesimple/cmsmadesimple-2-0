<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
#CMS - CMS Made Simple
#(c)2004-2008 by Ted Kulp (ted@cmsmadesimple.org)
#This project's homepage is: http://cmsmadesimple.org
#
#This program is free software; you can redistribute it and/or modify
#it under the terms of the GNU General Public License as published by
#the Free Software Foundation; either version 2 of the License, or
#(at your option) any later version.
#
#This program is distributed in the hope that it will be useful,
#but WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.
#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class to easily add attributes to your ORM class.
 * To use this class:
 * 1. Create variables named $attr_module and $attr_extra in your class.
 * 2. Set them to the name of your module and an optional "namespace"
 *    (ex. $attr_module = 'MyModule';  $attr_extra = 'Widget';)
 *
 * @since 2.0
 * @author Ted Kulp
 **/
class CmsActsAsAttributed extends SilkActsAs
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_attr_module(&$obj)
	{
		$attr_module = $obj->attr_module;
		if (!$attr_module)
			$attr_module = '';
			
		return $attr_module;
	}
	
	function get_attribute_names(&$obj, $lang = 'en_US')
	{
		$db_prefix = db_prefix();
		
		$attr_module = $this->get_attr_module($obj);
		
		return db()->GetArray("SELECT name FROM {$db_prefix}attributes WHERE module = ? AND class_name = ? AND object_id = ? AND lang = ?", array($attr_module, get_class($obj), $obj->id, $lang));
	}
	
	function get_all_attributes(&$obj, $lang = 'en_US')
	{
		$attr_module = $this->get_attr_module($obj);
		
		return orm('CmsAttribute')->find_all(array('conditions' => array('module = ? AND class_name = ? AND object_id = ? AND lang = ?', $attr_module, get_class($obj), $obj->id, $lang)));
	}
	
	function get_attribute_obj(&$obj, $attribute_name, $lang = 'en_US')
	{
		$attr_module = $this->get_attr_module($obj);
		
		return orm('CmsAttribute')->find(array('conditions' => array('module = ? AND class_name = ? AND object_id = ? AND name = ? AND lang = ?', $attr_module, get_class($obj), $obj->id, $attribute_name, $lang)));
	}
	
	function get_attribute(&$obj, $attribute_name, $lang = 'en_US')
	{
		$attribute = $this->get_attribute_obj(&$obj, $attribute_name, $lang);
		if ($attribute != null)
			return $attribute->content;

		return null;
	}
	
	function set_attribute(&$obj, $attribute_name, $attribute_value, $lang = 'en_US')
	{
		$attribute = $this->get_attribute_obj(&$obj, $attribute_name, $lang);
		if ($attribute != null)
		{
			$attribute->content = $attribute_value;
			return $attribute->save();
		}

		return false;
	}
}

# vim:ts=4 sw=4 noet
?>