<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
#CMS - CMS Made Simple
#(c)2004-2010 by Ted Kulp (ted@cmsmadesimple.org)
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

class CmsBelongsTo extends CmsObject
{
	private $_obj = null;
	private $_parent_class = '';
	private $_foreign_key = '';
	private $_data = null;

	function __construct($obj, $field_definition)
	{
		parent::__construct();
		
		$this->_obj = $obj;
		$this->_parent_class = $field_definition['parent_object'];
		$this->_foreign_key = $field_definition['foreign_key'];
		
		$this->fill_data();
	}
	
	function get_data()
	{
		return $this->_data;
	}
	
	private function fill_data()
	{
		if ($this->_parent_class != '' && $this->_foreign_key != '')
		{
			$class = new $this->_parent_class;
			if ($this->_obj->{$this->_foreign_key} > -1)
			{
				//$belongs_to = call_user_func_array(array($class, 'find_by_id'), $obj->{$this->child_field});
				//$belongs_to = $class->find_by_id($obj->{$this->child_field});
				//$obj->set_association($this->association_name, $belongs_to);
				
				$conditions = array($this->_foreign_key => $this->_obj->{$this->_obj->get_id_field()});
				$this->_data = $class->first($conditions)->execute();
			}
		}
		
	}
}

# vim:ts=4 sw=4 noet