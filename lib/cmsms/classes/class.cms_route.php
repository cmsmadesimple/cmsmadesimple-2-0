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
#
#$Id$

/**
 * Class to handle url routes for modules to handle pretty urls.
 *
 * @author Ted Kulp
 * @since 2.0
 **/
class CmsRoute extends SilkObject
{
	var $module;
	var $regex;
	var $defaults;

	function __construct()
	{
		parent::__construct();
	}
	
	/*
	public static function run($params, $page)
	{
		$page_obj = SilkCache::get_instance()->call('CmsRoute::match_route', $page);
		if ($page_obj)
		{
			echo SilkCache::get_instance()->call(array($page_obj, 'display'));
		}
		
		//TODO: Remove me
		echo SilkProfiler::get_instance()->report();
	}
	*/
	
	public static function run($params, $page)
	{
		CmsModuleLoader::load_module_data();
		
		echo SilkCache::get_instance()->call('CmsRoute::_run', $params, $page);
		
		//TODO: Remove me
		echo SilkProfiler::get_instance()->report();
	}
	
	public static function _run($params, $page)
	{
		$page_obj = CmsRoute::match_route($page);
		if ($page_obj)
		{
			return $page_obj->display();
		}
		
		return '';
	}
	
	public static function match_route($page)
	{
		$page_obj = null;
		
		if ($page == '' || $page == '/')
			$page_obj = orm('CmsPage')->find_by_default_page(true);
		else
			$page_obj = orm('CmsPage')->find_by_hierarchy_path($page);
		
		if ($page_obj == null)
		{
			var_dump('404 here');
		}
	
		return $page_obj;
	}
}

# vim:ts=4 sw=4 noet
?>