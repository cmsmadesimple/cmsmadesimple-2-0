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

namespace cmsms\auth;

use \silk\database\datamapper\DataMapper;

/**
 * Generic user class.  This can be used for any logged in user or user related function.
 *
 * @author Ted Kulp
 * @since 2.0
 * @version $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license GPL
 **/
class User extends DataMapper
{
	/*
	var $params = array('id' => -1, 'username' => '', 'password' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'active' => false);
	var $field_maps = array('first_name' => 'firstname', 'last_name' => 'lastname', 'admin_access' => 'adminaccess', 'username' => 'name');
	var $table = 'users';
	 */

	var $_fields = array(
		'id' => array(
			'type' => 'int',
			'primary' => true,
			'serial' => true,
		),
		'username' => array(
			'type' => 'string',
			'length' => '100'
		),
		'password' => array(
			'type' => 'string',
			'length' => '100'
		),
		'first_name' => array(
			'type' => 'string',
			'length' => '100'
		),
		'last_name' => array(
			'type' => 'string',
			'length' => '100'
		),
		'email' => array(
			'type' => 'string'
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
		'groups' => array(
			'type' => 'association',
			'association' => 'has_and_belongs_to_many'
		),
		/*
		'bookmarks' => array(
			'type' => 'association',
			'association' => 'has_many'
		),
		*/
	);
	
	public function __construct()
	{
		parent::__construct();
	}

	public function validate()
	{
		$this->validate_not_blank('username', __('nofieldgiven',array('username')));
		
		// Username validation
		if ($this->name != '')
		{
			// Make sure the name is unique
			$result = $this->find_by_name($this->name);
			if ($result)
			{
				if ($result->id != $this->id)
				{
					$this->add_validation_error(__('The username is already in use'));
				}
			}
			
			// Make sure the name has no illegal characters
			if ( !preg_match("/^[a-zA-Z0-9\.]+$/", $this->name) ) 
			{
				$this->add_validation_error(__('illegalcharacters', array(__('username'))));
			} 

			// Make sure the name is a valid length
			// the min_username_length member may not exist
			if( $this->min_username_length != null) 
			{
				if( strlen($this->name) < $this->min_username_length )
				{
					$this->add_validation_error(__('The username is too short'));
				}
			}

			// the minimum password length
			// the min_password_length member may not exist
			if( ($this->min_password_length != null) && 
				($this->clear_password != null) )
			{
				if( strlen($this->clear_password) < $this->min_password_length )
				{
					$this->add_validation_error(__('The password is too short'));
				}
			}

			// the clear_repeat_password and clear_password members
			// may not exist, but if they do, verify that they
			// match.
			if( ($this->clear_repeat_password != null) && 
				($this->clear_password != null) )
			{
				if( $this->clear_repeat_password != $this->clear_password )
				{
					$this->add_validation_error(__('The passwords do not match'));
				}
			}
		}
	}	

	function setup($first_time = false)
	{
		/*
		$this->create_has_many_association('bookmarks', 'bookmark', 'user_id');
		$this->create_has_and_belongs_to_many_association('groups', 'group', 'user_groups', 'group_id', 'user_id');
		*/
	}

	/**
	 * Checks the password of the user
	 *
	 * @param string The password to check
	 * @return bool If the password is correct
	 */
	public function check_password($password)
	{
		return md5($password) == $this->params['password'];
	}

	/**
	 * Encrypts and sets password for the User
	 *
	 * @param string The password to encrypt and set for the user
	 */
	public function set_password($password)
	{
		//Set params directly so that we don't get caught in a loop
		$this->params['password'] = md5($password);
		$this->dirty = true;
	}
	
	//Callback handlers
	protected function before_save()
	{
		//CmsEvents::send_event( 'Core', ($this->id == -1 ? 'AddUserPre' : 'EditUserPre'), array('user' => &$this));
	}
	
	protected function after_save()
	{
		//CmsEvents::send_event( 'Core', ($this->create_date == $this->modified_date ? 'AddUserPost' : 'EditUserPost'), array('user' => &$this));
	}
	
	protected function before_delete()
	{
		//CmsEvents::send_event('Core', 'DeleteUserPre', array('user' => &$this));
	}
	
	protected function after_delete()
	{
		//CmsEvents::send_event('Core', 'DeleteUserPost', array('user' => &$this));
	}
	
	public function is_anonymous()
	{
		return false;
	}
	
	public function full_name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
}

# vim:ts=4 sw=4 noet
