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

#########################################################
# Parts of this code are based/taken from phpDataMapper #
# Homepage: http://phpdatamapper.com/                   #
# Released under the MIT license                        #
#########################################################

class CmsPdoQuery extends CmsObject implements Countable, IteratorAggregate
{
	protected $_datasource = null;
	
	public $fields = array();
	public $table;
	public $conditions = array();
	public $order = array();
	public $group = array();
	public $limit;
	public $limit_offset;
	
	public function __construct(CmsPdoDatabase $datasource)
	{
		$this->_datasource = $datasource;
	}

	public function select($fields = '*')
	{
		$this->fields = (is_string($fields) ? explode(',', $fields) : $fields);
		return $this;
	}

	public function from($table)
	{
		$this->table = $table;
		return $this;
	}
	
	public function where(array $conditions = array(), $type = "AND", $setType = "AND")
	{
		$where = array();
		$where['conditions'] = $conditions;
		$where['type'] = $type;
		$where['setType'] = $set_type;

		$this->conditions[] = $where;
		return $this;
	}
	public function or_where(array $conditions = array(), $type = "AND")
	{
		return $this->where($conditions, $type, "OR");
	}
	
	public function and_where(array $conditions = array(), $type = "AND")
	{
		return $this->where($conditions, $type, "AND");
	}
	
	public function order($fields = array())
	{
		$order_by = array();
		$default_sort = "ASC";
		
		if (is_array($fields))
		{
			foreach ($fields as $field=>$sort)
			{
				// Numeric index - field as array entry, not key/value pair
				if (is_numeric($field))
				{
					$field = $sort;
					$sort = $default_sort;
				}

				$this->order[$field] = strtoupper($sort);
			}
		}
		else
		{
			$this->order[$fields] = $default_sort;
		}
		return $this;
	}
	
	public function group(array $fields = array())
	{
		foreach($fields as $field)
		{
			$this->group[] = $field;
		}
		return $this;
	}
	
	public function limit($limit = 20, $offset = null)
	{
		$this->limit = $limit;
		$this->limit_offset = $offset;
		return $this;
	}

	public function execute()
	{
		return $this->_datasource->read($this);
	}
	
	public function first()
	{
		$result = $this->limit(1)->execute();
		return ($result !== false && !empty($result)) ? $result[0] : false;
	}
	
	public function params()
	{
		$params = array();
		foreach($this->conditions as $i=>$data)
		{
			if(isset($data['conditions']) && is_array($data['conditions']))
			{
				foreach($data['conditions'] as $field => $value)
				{
					$params[$field] = $value;
				}
			}
		}
		return $params;
	}
	
	public function count()
	{
		// Execute query and return count
		$result = $this->execute();
		return ($result !== false) ? count($result) : 0;
	}
	
	public function getIterator()
	{
		// Execute query and return result set for iteration
		$result = $this->execute();
		return ($result !== false) ? new ArrayIterator($result) : array();
	}

}

# vim:ts=4 sw=4 noet