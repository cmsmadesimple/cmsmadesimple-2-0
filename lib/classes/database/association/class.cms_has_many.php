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

class CmsHasMany extends CmsObject implements Countable, IteratorAggregate, ArrayAccess
{
	private $_obj = null;
	private $_child_class = '';
	private $_foreign_key = '';
	private $_data = array();

	function __construct($obj, $field_definition)
	{
		parent::__construct();
		
		$this->_obj = $obj;
		$this->_child_class = $field_definition['child_object'];
		$this->_foreign_key = $field_definition['foreign_key'];
		
		$this->fill_data();
	}
	
	function get_data()
	{
		return $this;
	}
	
	private function fill_data()
	{
		if ($this->_child_class != '' && $this->_foreign_key != '')
		{
			$class = new $this->_child_class;
			if ($this->_obj->{$this->_obj->get_id_field()} > -1)
			{
				/*
				$queryattrs = $this->extra_params;
				$conditions = "{$this->_foreign_key} = ?";
				$params = array($obj->{$obj->get_id_field()});
			
				if (array_key_exists('conditions', $this->extra_params))
				{
					$conditions = "({$conditions}) AND ({$this->extra_params['conditions'][0]})";
					if (count($this->extra_params['conditions']) > 1)
					{
						$params = array_merge($params, array_slice($this->extra_params['conditions'], 1));
					}
				}
				$queryattrs['conditions'] = array_merge(array($conditions), $params);
				*/
				
				$conditions = array($this->_foreign_key => $this->_obj->{$this->_obj->get_id_field()});
				$this->_data = $class->all($conditions)->execute();
				
				//If we got a single, we still need an array for in here
				if (!is_array($this->_data))
					$this->_data = array($this->_data);
			}
		}
	}
	
	public function count()
	{
		// Load related records for current row
		return count($this->_data);
	}

	public function getIterator()
	{
		// Load related records for current row
		return $this->_data ? $this->_data : array();
	}
	
	public function offsetExists($key)
	{
		return isset($this->_data[$key]);
	}

	public function offsetGet($key)
	{
		return $this->_data[$key];
	}

	public function offsetSet($key, $value)
	{
		if ($key === null)
		{
			return $this->_data[] = $value;
		}
		else
		{
			return $this->_data[$key] = $value;
		}
	}

	public function offsetUnset($key)
	{
		unset($this->_data[$key]);
	}
}

# vim:ts=4 sw=4 noet