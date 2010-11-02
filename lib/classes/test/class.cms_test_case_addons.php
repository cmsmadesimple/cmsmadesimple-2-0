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

class CmsTestCaseAddons extends CmsObject
{
	private $parent_obj = null;
	
	function __construct($parent_obj)
	{
		parent::__construct();
		$this->parent_obj = $parent_obj;
	}
	
	function get_database()
	{
		return cms_db();
	}
	
	function get_url_base()
	{
		return CmsRequest::get_calculated_url_base(true);
	}
	
	function get_admin_url_base()
	{
		return $this->get_url_base() . '/' . cms_config()->get('admin_dir');
	}
	
	function random_string($length = 8)
	{
		$str = '';
		for ($i = 0; $i < $length; $i++)
		{
			$str .= chr(mt_rand(32, 126));
		}
		return $str;
	}
	
	function random_name($length = 8)
	{
		$values = array_merge(range(65, 90), range(97, 122), range(48, 57));
		$max = count($values) - 1;
		$str = chr(mt_rand(97, 122));
		for ($i = 1; $i < $length; $i++)
		{
			$str .= chr($values[mt_rand(0, $max)]);
		}
		return $str;
	}
	
	function create_user()
	{
		$user = new CmsUser;
		$username = $this->random_name();
		$user->name = $username;
		$user->first_name = $username;
		$user->last_name = $this->random_name();
		$user->email = $username . '@test.com';
		$user->active = 1;
		
		$pw = $this->random_string();
		$user->decoded_password = $pw;
		$user->set_password($pw);
		
		$user->save();
		
		return $user;
	}
	
	function delete_user($user)
	{
		return $user->delete();
	}
}

# vim:ts=4 sw=4 noet