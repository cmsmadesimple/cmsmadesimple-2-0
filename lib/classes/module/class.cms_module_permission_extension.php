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

class CmsModulePermissionExtension extends CmsModuleExtension
{
	function __construct($module)
	{
		parent::__construct($module);
	}

	/**
	* Create's a new permission for use by the module.
	*
	* @param string Name of the permission to create
	* @param string The extra attribute of the permission to remove
	* @param bool todo dev
	* @param string todo dev
	*/
	public function create($permission_name, $extra_attr = '', $hierarchical = false, $table = '')
	{
		CmsAcl::create_permission_definition($permission_name,$this->module->get_name(), $extra_attr, $hierarchical, $table);
	}

	/**
	* Checks a permission against the currently logged in user.
	*
	* @param string The name of the permission to check against the current user
	* @param string The extra attribute of the permission to remove
	* @param bool todo dev
	* @param string todo dev
	*/
	public function check($permission_name, $extra_attr = '', $hierarchical = false, $table = '')
	{
		$user = CmsLogin::get_current_user();
		$tmp = CmsAcl::check_permission($permission_name, $user, $this->module->get_name(), $extra_attr, $hierarchical, $table);
		return $tmp;
	}

	/**
	* Removes a permission from the system.  If recreated, the
	* permission would have to be set to all groups again.
	*
	* @param string The name of the permission to remove
	* @param string The extra attribute of the permission to remove
	*/
	public function remove($permission_name, $extra_attr = '')
	{
		if (!CmsAcl::delete_permission_definition($permission_name, $this->module->get_name()), $extra_attr )
		{
				return CmsAcl::delete_permission_definition($permission_name, 'Core', $extra_attr);
		}
	}
}

# vim:ts=4 sw=4 noet
?>
