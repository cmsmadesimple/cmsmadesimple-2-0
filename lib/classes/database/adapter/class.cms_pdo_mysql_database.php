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

class CmsPdoMysqlDatabase extends CmsPdoDatabase
{
	protected $format_date = "Y-m-d";
	protected $format_time = " H:i:s";
	protected $format_datetime = "Y-m-d H:i:s";

	// Driver-Specific settings
	protected $_engine = 'InnoDB';
	protected $_charset = 'utf8';
	protected $_collate = 'utf8_unicode_ci';

	protected $_fieldTypeMap = array(
		'string' => array('adapter_type' => 'varchar', 'length' => 255),
		'text' => array('adapter_type' => 'text'),
		'int' => array('adapter_type' => 'int'),
		'integer' => array('adapter_type' => 'int'),
		'bool' => array('adapter_type' => 'tinyint', 'length' => 1),
		'boolean' => array('adapter_type' => 'tinyint', 'length' => 1),
		'float' => array('adapter_type' => 'float'),
		'double' => array('adapter_type' => 'double'),
		'date' => array('adapter_type' => 'date'),
		'datetime' => array('adapter_type' => 'datetime'),
		'create_date' => array('adapter_type' => 'datetime'),
		'modified_date' => array('adapter_type' => 'datetime'),
		'time' => array('adapter_type' => 'time'),
	);
	
	function __construct($dsn, $username, $password, $driver_options = array())
	{
		parent::__construct($dsn, $username, $password, $driver_options);
	}
	
	static public function get_connection_attributes()
	{
		return array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
	}
	
	/**
	 * Get columns for current table
	 *
	 * @param String $table Table name
	 * @return Array
	 */
	public function getColumnsForTable($table)
	{
		$tableColumns = array();
		$tblCols = $this->query("SELECT * FROM information_schema.columns WHERE table_schema = '" . $this->_database_name . "' AND table_name = '" . $table . "'");

		if($tblCols)
		{
			while($columnData = $tblCols->fetch(PDO::FETCH_ASSOC))
			{
				$tableColumns[$columnData['COLUMN_NAME']] = $columnData;
			}
			return $tableColumns;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Syntax for each column in CREATE TABLE command
	 *
	 * @param string $fieldName Field name
	 * @param array $fieldInfo Array of field settings
	 * @return string SQL syntax
	 */
	public function migrateSyntaxFieldCreate($fieldName, array $fieldInfo)
	{
		// Ensure field type exists
		if(!isset($this->_fieldTypeMap[$fieldInfo['type']])) {
			//throw new phpDataMapper_Exception("Field type '" . $fieldInfo['type'] . "' not supported");
			//var_dump("Field type '" . $fieldInfo['type'] . "' not supported");
			return null;
		}

		$fieldInfo = array_merge($fieldInfo, $this->_fieldTypeMap[$fieldInfo['type']]);

		$syntax = "`" . $fieldName . "` " . $fieldInfo['adapter_type'];
		// Column type and length
		$syntax .= isset($fieldInfo['length']) ? '(' . $fieldInfo['length'] . ')' : '';
		// Unsigned
		$syntax .= isset($fieldInfo['unsigned']) ? ' unsigned' : '';
		// Collate
		$syntax .= ($fieldInfo['type'] == 'string' || $fieldInfo['type'] == 'text') ? ' COLLATE ' . $this->_collate : '';
		// Nullable
		$isNullable = true;
		if((isset($fieldInfo['required']) && $fieldInfo['required']) || !isset($fieldInfo['null']) || !$fieldInfo['null']) {
			$syntax .= ' NOT NULL';
			$isNullable = false;
		}
		// Default value
		if((!isset($fieldInfo['default']) || $fieldInfo['default'] === null) && $isNullable) {
			$syntax .= " DEFAULT NULL";
		} elseif(isset($fieldInfo['default']) && $fieldInfo['default'] !== null) {
			$default = $fieldInfo['default'];
			// If it's a boolean and $default is boolean then it should be 1 or 0
			if ( is_bool($default) && $fieldInfo['type'] == "boolean" ) {
				$default = $default ? 1 : 0;
			}
			$syntax .= " DEFAULT '" . $default . "'";
		}
		// Extra
		$syntax .= (isset($fieldInfo['primary']) && $fieldInfo['primary'] && isset($fieldInfo['serial']) && $fieldInfo['serial']) ? ' AUTO_INCREMENT' : '';
		return $syntax;
	}


	/**
	 * Syntax for CREATE TABLE with given fields and column syntax
	 *
	 * @param string $table Table name
	 * @param array $formattedFields Array of fields with all settings
	 * @param array $columnsSyntax Array of SQL syntax of columns produced by 'migrateSyntaxFieldCreate' function
	 * @return string SQL syntax
	 */
	public function migrateSyntaxTableCreate($table, array $formattedFields, array $columnsSyntax)
	{
		$syntax = "CREATE TABLE IF NOT EXISTS `" . $table . "` (\n";
		// Columns
		$syntax .= implode(",\n", $columnsSyntax);

		// Keys...
		$ki = 0;
		$usedKeyNames = array();
		foreach($formattedFields as $fieldName => $fieldInfo) {
			// Determine key field name (can't use same key name twice, so we have to append a number)
			$fieldKeyName = $fieldName;
			while(in_array($fieldKeyName, $usedKeyNames)) {
				$fieldKeyName = $fieldName . '_' . $ki;
			}
			// Key type
			if(isset($fieldInfo['primary'])) {
				$syntax .= "\n, PRIMARY KEY(`" . $fieldName . "`)";
			}
			if(isset($fieldInfo['unique'])) {
				$syntax .= "\n, UNIQUE KEY `" . $fieldKeyName . "` (`" . $fieldName . "`)";
				$usedKeyNames[] = $fieldKeyName;
			}
			if(isset($fieldInfo['index'])) {
				$syntax .= "\n, KEY `" . $fieldKeyName . "` (`" . $fieldName . "`)";
				$usedKeyNames[] = $fieldKeyName;
			}
		}

		// Extra
		$syntax .= "\n) ENGINE=" . $this->_engine . " DEFAULT CHARSET=" . $this->_charset . " COLLATE=" . $this->_collate . ";";

		return $syntax;
	}


	/**
	 * Syntax for each column in CREATE TABLE command
	 *
	 * @param string $fieldName Field name
	 * @param array $fieldInfo Array of field settings
	 * @return string SQL syntax
	 */
	public function migrateSyntaxFieldUpdate($fieldName, array $fieldInfo, $add = false)
	{
		return ( $add ? "ADD COLUMN " : "MODIFY " ) . $this->migrateSyntaxFieldCreate($fieldName, $fieldInfo);
	}


	/**
	 * Syntax for ALTER TABLE with given fields and column syntax
	 *
	 * @param string $table Table name
	 * @param array $formattedFields Array of fields with all settings
	 * @param array $columnsSyntax Array of SQL syntax of columns produced by 'migrateSyntaxFieldUpdate' function
	 * @return string SQL syntax
	 */
	public function migrateSyntaxTableUpdate($table, array $formattedFields, array $columnsSyntax)
	{
		/*
			ALTER TABLE `posts`
			CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			CHANGE `status` `status` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'draft'
		*/
		$syntax = "ALTER TABLE `" . $table . "` \n";
		// Columns
		$syntax .= implode(",\n", $columnsSyntax);


		// Keys...
		$ki = 0;
		$usedKeyNames = array();
		foreach($formattedFields as $fieldName => $fieldInfo) {
			// Determine key field name (can't use same key name twice, so we  have to append a number)
			$fieldKeyName = $fieldName;
			while(in_array($fieldKeyName, $usedKeyNames)) {
				$fieldKeyName = $fieldName . '_' . $ki;
			}
			// Key type
			if($fieldInfo['primary']) {
				$syntax .= ",\n PRIMARY KEY(`" . $fieldName . "`)";
			}
			if($fieldInfo['unique']) {
				$syntax .= ",\n UNIQUE KEY `" . $fieldKeyName . "` (`" . $fieldName . "`)";
				$usedKeyNames[] = $fieldKeyName;
				 // Example: ALTER TABLE `posts` ADD UNIQUE (`url`)
			}
			if($fieldInfo['index']) {
				$syntax .= ",\n KEY `" . $fieldKeyName . "` (`" . $fieldName . "`)";
				$usedKeyNames[] = $fieldKeyName;
			}
		}

		// Extra
		$syntax .= ";";
		return $syntax;
	}
}

# vim:ts=4 sw=4 noet