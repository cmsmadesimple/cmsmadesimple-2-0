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
#BUT withOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.
#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

namespace cmsms\auth;

use \silk\database\datamapper\DataMapper;

/**
 * Represents a user group in the database.
 *
 * @author Ted Kulp
 * @since 2.0
 * @version $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license GPL
 **/
class Group extends DataMapper
{
	var $_fields = array(
		'id' => array(
			'type' => 'int',
			'primary' => true,
			'serial' => true,
		),
		'name' => array(
			'type' => 'string',
			'length' => '100',
			'aliases' => array(
				'group_name'
			)
		),
		'active' => array(
			'type' => 'boolean',
			'default' => true,
		),
		'create_date' => array(
			'type' => 'create_date'
		),
		'modified_date' => array(
			'type' => 'modified_date'
		),
		'extra_params' => array(
			'type' => 'text'
		),
		'user_groups' => array(
			'type' => 'table',
			'fields' => array(
				'user_id' => array(
					'type' => 'integer'
				),
				'group_id' => array(
					'type' => 'integer'
				),
				'create_date' => array(
					'type' => 'create_date'
				),
				'modified_date' => array(
					'type' => 'modified_date'
				),
			),
		),
		'users' => array(
			'type' => 'association',
			'association' => 'has_and_belongs_to_many'
		),
	);
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function setup()
	{
		//$this->create_has_and_belongs_to_many_association('users', 'user', 'user_groups', 'user_id', 'group_id');
	}
	
	public function validate()
	{
		$this->validate_not_blank('name', lang('nofieldgiven',array(lang('username'))));
		
		// Username validation
		if ($this->name != '')
		{
			// Make sure the name is unique
			$result = $this->find_by_name($this->name);
			if ($result)
			{
				if ($result->id != $this->id)
				{
					$this->add_validation_error(lang('The group name is already in use'));
				}
			}
			
			// Make sure the name has no illegal characters
			if ( !preg_match("/^[a-zA-Z0-9\.]+$/", $this->name) ) 
			{
				$this->add_validation_error(lang('illegalcharacters', array(lang('groupname'))));
			}
		}
	}

	public function add_user($user)
	{
		if ($this->id > -1)
		{
			$date = db()->timestamp(time());
			return db()->execute_sql("INSERT INTO {user_groups} (user_id, group_id, create_date, modified_date) VALUES (?,?,{$date},{$date})", array($user->id, $this->id));
		}
		
		return false;
	}

	public function remove_user($user)
	{
		if ($this->id > -1)
		{
			return db()->execute_sql('DELETE FROM {user_groups} WHERE user_id = ? AND group_id = ?', array($user->id, $this->id));
		}

		return false;
	}
	
	//Callback handlers
	public function before_save()
	{
		CmsEvents::send_event( 'Core', ($this->id == -1 ? 'AddGroupPre' : 'EditGroupPre'), array('group' => &$this));
	}
	
	public function after_save()
	{
		//Add the group to the aro table so we can do acls on it
		//Only happens on a new insert
		if ($this->create_date == $this->modified_date)
		{
			//CmsAcl::add_aro($this->id, 'Group');
		}
		CmsEvents::send_event( 'Core', ($this->create_date == $this->modified_date ? 'AddGroupPost' : 'EditGroupPost'), array('group' => &$this));
	}
	
	public function before_delete()
	{
		db()->execute_sql('DELETE FROM {user_groups} WHERE group_id = ?', array($this->id));
		CmsEvents::send_event('Core', 'DeleteGroupPre', array('group' => &$this));
	}
	
	public function after_delete()
	{
		//CmsAcl::delete_aro($this->id, 'Group');
		CmsEvents::send_event('Core', 'DeleteGroupPost', array('group' => &$this));
	}
	
	public static function get_groups_for_dropdowm($add_everyone = false)
	{
		$result = array();
		
		if ($add_everyone)
		{
			$result[-1] = lang('Everyone');
		}

		$groups = self::all()->order('name ASC');
		foreach ($groups as $group)
		{
			$result[$group->id] = $group->name;
		}
		
		return $result;
	}
}

# vim:ts=4 sw=4 noet
