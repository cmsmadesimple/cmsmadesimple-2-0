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

/**
 * ORM class for handling page type lookups in the database.
 * The confusing aspect to this is that the page types themsevles
 * are actually implemented by different classes -- classes that extend
 * CmsPage.  We do this because modules may implement their page
 * types in a totally independant way than a 1:1 relation.  It also allows us
 * to use the ORM to our advantage by using the $type lookup trick to easily
 * instantiate the right content object of the right type in one swoop.
 *
 * This particular class is only meant as a mapping to the records in the
 * page_types table.  This class also performs some basic lookups, and
 * page type related methods.
 *
 * @package CMS
 * @author Ted Kulp
 */
class CmsPageType extends CmsObjectRelationalMapping
{
	var $table = 'page_types';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Returns an array of all the registered page types in the
	 * system.
	 *
	 * @return array List of page types.
	 */
	public static function get_all_page_types()
	{
		return cms_orm('CmsPageType')->find_all();
	}
	
	/**
	 * Returns the "default" page type for the system. Currently, just
	 * grabs the first in the database table. This will later be controlled
	 * by a preference.
	 *
	 * @return mixed The default page type.
	 * @author Ted Kulp
	 */
	public static function get_default_page_type()
	{
		return cms_orm('CmsPageType')->find(array('order' => 'id'));
	}
	
	/**
	 * Returns the name of the class for the given page type.
	 *
	 * @param string $name The name of the page type to look up
	 * @return string The name of the class for that page type
	 * @author Ted Kulp
	 */
	public static function get_page_type_class_by_type($name, $instantiate = false)
	{
		$type = cms_orm('CmsPageType')->find_by_name($name);
		if ($type != null && is_a($type, 'CmsPageType'))
		{
			if ($instantiate)
			{
				$ret = self::get_page_type_obj($type);
				if ($ret)
					return $ret;
			}
			else
			{
				$ret = self::get_page_type_class($type);
				if ($ret)
					return $ret;
			}
		}
		return null;
	}

	/**
	 * Returns the implementing object for the given page type
	 * ORM object.
	 *
	 * @param CmsPageType $type The class to look up
	 * @return mixed An instance of the implmenting object
	 * @author Ted Kulp
	 */
	public static function get_page_type_obj(CmsPageType $type)
	{
		if ($type)
		{
			if ($type->module == 'Core')
			{
				if (class_exists($type->class))
					return new $type->class;
			}
		}
		return null;
	}
	
	/**
	 * Returns the implementing classname for the given page type
	 * ORM object.
	 *
	 * @param CmsPageType $type The class to look up
	 * @return string The name of the implmenting class
	 * @author Ted Kulp
	 */
	public static function get_page_type_class(CmsPageType $type)
	{
		if ($type)
		{
			if ($type->module == 'Core')
			{
				if (class_exists($type->class))
					return $type->class;
			}
		}
		return null;
	}
}

# vim:ts=4 sw=4 noet
?>