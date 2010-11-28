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

abstract class CmsPdoDatabase extends PDO
{
	protected $query_log = array();
	protected $_database_name = '';
	
	static private $instance = NULL;
	
	function __construct($dsn, $username, $password, $driver_options = array())
	{
		parent::__construct($dsn, $username, $password, $driver_options);
	}
	
	/**
	 * Returns an instnace of the CmsPdoDatabase (or one of it's 
	 * children) singleton.  Most people can generally use cmsms() 
	 * instead of this, but they both do the same thing.
	 *
	 * @return CmsPdoDatabase The singleton CmsPdoDatabase instance
	 * @author Ted Kulp
	 **/
	static public function get_instance()
	{
		if (self::$instance == NULL)
		{
			$config = cms_config();
			
			$conn_string = $config['dbms'] . ':dbname=' . $config['db_name'] . ';host=' . $config['db_hostname'];
			if ($config['db_port'] != '')
				$conn_string .= ';port=' . $config['db_port'];
			
			$attrs = array(PDO::ATTR_PERSISTENT => true);
			
			try
			{
				$class = 'CmsPdoMysqlDatabase';
				$attrs = $attrs + call_user_func_array($class.'::get_connection_attributes', array()); //Add driver specific attributes
				self::$instance = new $class($conn_string, $config['db_username'], $config['db_password'], $attrs);
				self::$instance->_database_name = $config['db_name'];
			}
			catch (PDOException $e)
			{
				echo 'Connection failed: ' . $e->getMessage();
			}
		}
		return self::$instance;
	}
	
    public function __call($function, $arguments) {
        //echo "Calling object method '$function'";debug_display($arguments);
		$ado_db = CmsDatabase::get_instance();
		if (method_exists($ado_db, $function))
		{
			return call_user_func_array(array($ado_db, $function), $arguments);
		}
    }

    /**  As of PHP 5.3.0  */
    public static function __callStatic($function, $arguments) {
        //echo "Calling static method '$function' ". implode(', ', $arguments). "<br />";
		if (method_exists('CmsDatabase', $function))
		{
			return call_user_func_array('CmsDatabase::'.$function, $arguments);
		}
    }

	public function query($query, $add_prefix = true)
	{
		$query = $add_prefix ? $this->add_prefix_to_query($query) : $query;
		return parent::query($query);
	}
	
	public function prepare($query, $driver_options = array())
	{
		$query = $this->add_prefix_to_query($query);
		return parent::prepare($query, $driver_options);
	}
	
	public function execute_sql($query, $input_parameters = array(), $driver_options = array())
	{
		$this->log_query($query, $input_parameters);
		$handle = $this->prepare($query, $driver_options);
		if ($handle)
		{
			return $handle->execute($input_parameters);
		}
		return false;
	}
	
	public function fetch_all($query, $input_parameters = array(), $driver_options = array())
	{
		$this->log_query($query, $input_parameters);
		$handle = $this->prepare($query, $driver_options);
		if ($handle)
		{
			if ($handle->execute($input_parameters))
			{
				return $handle->fetchAll();
			}
		}
		return false;
	}
	
	public function fetchAll($query, $input_parameters = array(), $driver_options = array())
	{
		$this->log_query($query, $input_parameters);
		return $this->fetch_all($query, $input_parameters, $driver_options);
	}
	
	public function fetch_column($query, $input_parameters = array(), $column_num = 0, $driver_options = array())
	{
		$this->log_query($query, $input_parameters);
		$handle = $this->prepare($query, $driver_options);
		if ($handle)
		{
			if ($handle->execute($input_parameters))
			{
				$result = array();
				while ($one_col = $handle->fetchColumn($column_num))
				{
					$result[] = $one_col;
				}
				return $result;
			}
		}
		return false;
	}
	
	public function get_one($query, $input_parameters = array(), $driver_options = array())
	{
		$this->log_query($query, $input_parameters);
		$handle = $this->prepare($query, $driver_options);
		if ($handle)
		{
			if ($handle->execute($input_parameters))
			{
				return $handle->fetchColumn();
			}
		}
		return false;
	}
	
	function last_insert_id($name = NULL)
	{
		return $this->lastInsertId($name);
	}
	
	function timestamp($time = null)
	{
		if (!$time)
			$time = time();
		
		return date($this->format_datetime, $time);
	}
	
	public function add_prefix_to_query($query)
	{
		return strtr($query, array('{' => cms_db_prefix(), '}' => ''));
	}
	
	public function log_query($sql, $data = null)
	{
		$this->query_log[] = array('query' => $sql, 'data' => $data);
	}
	
	public function select($fields = "*")
	{
		$query = new CmsPdoQuery($this);
		$query->select($fields);
		return $query;
	}
	
	public function read(CmsPdoQuery $query)
	{
		$conditions = $this->statement_conditions($query->conditions);
		$binds = $this->statement_binds($query->params());
		$order = array();
		if ($query->order)
		{
			foreach($query->order as $oField => $oSort)
			{
				$order[] = $oField . " " . $oSort;
			}
		}

		$sql = "
			SELECT " . $this->statement_fields($query->fields) . "
			FROM " . $query->table . "
			" . ($conditions ? 'WHERE ' . $conditions : '') . "
			" . ($query->group ? 'GROUP BY ' . implode(', ', $query->group) : '') . "
			" . ($order ? 'ORDER BY ' . implode(', ', $order) : '') . "
			" . ($query->limit ? 'LIMIT ' . $query->limit : '') . " " . ($query->limit && $query->limit_offset ? 'OFFSET ' . $query->limit_offset: '') . "
			";

		// Unset any NULL values in binds (compared as "IS NULL" and "IS NOT NULL" in SQL instead)
		if($binds && count($binds) > 0)
		{
			foreach($binds as $field => $value)
			{
				if (null === $value)
				{
					unset($binds[$field]);
				}
			}
		}

		// Add query to log
		$this->log_query($sql, $binds);

		// Prepare update query
		$handle = $this->prepare($sql);
		if ($handle)
		{
			if ($handle->execute($binds))
			{
				return $handle->fetchAll();
			}
		}
		
		return false;
	}
	
	public function statement_conditions(array $conditions = array())
	{
		if (count($conditions) == 0)
		{
			return;
		}

		$sqlStatement = "";
		$defaultColOperators = array(0 => '', 1 => '=');
		$ci = 0;
		$loopOnce = false;
		foreach ($conditions as $condition)
		{
			if (is_array($condition) && isset($condition['conditions']))
			{
				$subConditions = $condition['conditions'];
			}
			else
			{
				$subConditions = $conditions;
				$loopOnce = true;
			}
			$sqlWhere = array();
			foreach ($subConditions as $column => $value)
			{
				// Column name with comparison operator
				$colData = explode(' ', $column);
				if ( count( $colData ) > 2 )
				{
					$operator = array_pop( $colData );
					$colData = array( join(' ', $colData), $operator );
				}
				$col = $colData[0];

				// Array of values, assume IN clause
				if(is_array($value))
				{
					$sqlWhere[] = $col . " IN('" . implode("', '", $value) . "')";
					// NULL value
				}
				elseif (is_null($value))
				{
					$sqlWhere[] = $col . " IS NULL";

					// Standard string value
				}
				else
				{
					$colComparison = isset($colData[1]) ? $colData[1] : '=';
					$columnSql = $col . ' ' . $colComparison;

					// Add to binds array and add to WHERE clause
					$colParam = preg_replace('/\W+/', '_', $col) . $ci;
					$sqlWhere[] = $columnSql . " :" . $colParam . "";
				}

				// Increment ensures column name distinction
				$ci++;
			}
			
			if ( $sqlStatement != "" )
			{
				$sqlStatement .= " " . (isset($condition['setType']) ? $condition['setType'] : 'AND') . " ";
			}
			//var_dump($condition);
			$sqlStatement .= join(" " . (isset($condition['type']) ? $condition['type'] : 'AND') . " ", $sqlWhere );

			if($loopOnce)
			{
				break;
			}
		}

		return $sqlStatement;
	}
	
	public function statement_binds(array $conditions = array())
	{
		if(count($conditions) == 0)
		{
			return;
		}

		$binds = array();
		$ci = 0;
		$loopOnce = false;
		foreach ($conditions as $condition)
		{
			if (is_array($condition) && isset($condition['conditions']))
			{
				$subConditions = $condition['conditions'];
			}
			else
			{
				$subConditions = $conditions;
				$loopOnce = true;
			}
			
			foreach ($subConditions as $column => $value)
			{
				// Can't bind array of values
				if(!is_array($value) && !is_object($value))
				{
					// Column name with comparison operator
					$colData = explode(' ', $column);
					if ( count( $colData ) > 2 )
					{
						$operator = array_pop( $colData );
						$colData = array( join(' ', $colData), $operator );
					}
					$col = $colData[0];
					$colParam = preg_replace('/\W+/', '_', $col) . $ci;

					// Add to binds array and add to WHERE clause
					$binds[$colParam] = $value;
				}

				// Increment ensures column name distinction
				$ci++;
			}
			
			if($loopOnce)
			{
				break;
			}
		}
		return $binds;
	}
	
	public function statement_fields(array $fields = array())
	{
		return count($fields) > 0 ? implode(', ', $fields) : "*";
	}
	
	public function migrate($table, array $fields)
	{
		// Get current fields for table
		$tableExists = false;
		$tableColumns = $this->getColumnsForTable(cms_db_prefix() . $table);

		if($tableColumns)
		{
			$tableExists = true;
		}
		
		if($tableExists)
		{
			// Update table
			$this->update_table($table, $fields);
		}
		else
		{
			// Create table
			$this->create_table($table, $fields);
		}
	}
	
	public function create_table($table, $fields = array())
	{
		// Prepare fields and get syntax for each
		$columns_syntax = array();
		foreach($fields as $field_name => $field_info)
		{
			$ret = $this->migrateSyntaxFieldCreate($field_name, $field_info);
			if ($ret)
				$columns_syntax[$field_name] = $ret;
		}

		// Get syntax for table with fields/columns
		$sql = $this->migrateSyntaxTableCreate(cms_db_prefix() . $table, $fields, $columns_syntax);

		// Add query to log
		$this->log_query($sql);

		$this->query($sql, false);
		
		return true;
	}
	
	public function update_table($table, array $formattedFields)
	{
		/*
			STEPS:
			* Use fields to get column syntax
			* Use column syntax array to get table syntax
			* Run SQL
		*/

		// Prepare fields and get syntax for each
		$tableColumns = $this->getColumnsForTable(cms_db_prefix() . $table);
		$updateFormattedFields = array();
		foreach($tableColumns as $fieldName => $columnInfo) {
			if(isset($formattedFields[$fieldName])) {
				// TODO: Need to do a more exact comparison and make this non-mysql specific
				if ( 
						$this->_fieldTypeMap[$formattedFields[$fieldName]['type']] != $columnInfo['DATA_TYPE'] ||
						$formattedFields[$fieldName]['default'] !== $columnInfo['COLUMN_DEFAULT']
					) {
					$updateFormattedFields[$fieldName] = $formattedFields[$fieldName];
				}

				unset($formattedFields[$fieldName]);
			}
		}

		$columnsSyntax = array();
		// Update fields whose options have changed
		foreach($updateFormattedFields as $fieldName => $fieldInfo) {
			$columnsSyntax[$fieldName] = $this->migrateSyntaxFieldUpdate($fieldName, $fieldInfo, false);
		}
		// Add fields that are missing from current ones
		foreach($formattedFields as $fieldName => $fieldInfo) {
			$columnsSyntax[$fieldName] = $this->migrateSyntaxFieldUpdate($fieldName, $fieldInfo, true);
		}

		// Get syntax for table with fields/columns
		if ( !empty($columnsSyntax) ) {
			$sql = $this->migrateSyntaxTableUpdate(cms_db_prefix() . $table, $formattedFields, $columnsSyntax);

			// Add query to log
			$this->log_query($sql);

			// Run SQL
			$this->query($sql, false);
		}
		return true;
	}

	public function drop_table($table)
	{
		$sql = "DROP TABLE " . cms_db_prefix() . $table;

		// Add query to log
		$this->log_query($sql);

		return $this->query($sql, false);
	}
	
	public function truncate_table($table)
	{
		$sql = "TRUNCATE TABLE " . cms_db_prefix() . $table;

		// Add query to log
		$this->log_query($sql);

		return $this->query($sql, false);
	}

}

# vim:ts=4 sw=4 noet
