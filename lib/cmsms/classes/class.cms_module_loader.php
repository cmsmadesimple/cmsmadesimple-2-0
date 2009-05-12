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

class CmsModuleLoader extends SilkObject
{
	public static $module_list = null;
	
	function __construct()
	{
		parent::__construct();
	}
	
	public static function load_module_data()
	{
		$files = CmsModuleLoader::find_module_info_files(); //Actually slower if it's cached -- have to retest with lots of modules
		$installed_data = SilkCache::get_instance()->call('CmsModuleLoader::get_installed_module_details');
		$module_list = array();
		foreach ($files as $one_file)
		{
			$module_data = self::xmlify_module_info_file($one_file);
			$module_data = self::inject_installed_data_for_module($module_data, $installed_data);
			$module_list[$module_data['name']] = $module_data;
		}
		
		self::check_core_version($module_list);
		self::check_dependencies($module_list);
	}
	
	public static function check_core_version(&$module_list)
	{
		foreach ($module_list as &$one_module)
		{
			if (isset($one_module['minimum_core_version']))
			{
				if (version_compare($one_module['minimum_core_version'], '2.0', '>')) //Use a real version number here
				{
					$one_module['meets_minimum_core'] = false;
					$one_module['active'] = false;
				}
			}
		}
	}
	
	public static function check_dependencies(&$module_list)
	{
		foreach ($module_list as $one_module)
		{
			self::check_dependencies_for_module($one_module['name'], $module_list);
		}
	}
	
	public static function check_dependencies_for_module($module_name, &$module_list)
	{
		//Make sure we haven't done this one yet -- no point in repeating
		if (!isset($module_list[$module_name]['meets_dependencies']))
		{
			$module_list[$module_name]['meets_dependencies'] = true;
			
			if (isset($module_list[$module_name]['dependencies']) && is_array($module_list[$module_name]['dependencies']))
			{
				//Hack for handling 1 module dependency
				if (isset($module_list[$module_name]['dependencies']['module']))
				{
					$tmp = $module_list[$module_name]['dependencies']['module'];
					unset($module_list[$module_name]['dependencies']['module']);
					$module_list[$module_name]['dependencies'][0]['module'] = $tmp;
				}
				
				for ($i = 0; $i < count($module_list[$module_name]['dependencies']); $i++)
				{
					//If a module dependency (only kind for now)
					if (isset($module_list[$module_name]['dependencies'][$i]['module']))
					{
						$one_dep = $module_list[$module_name]['dependencies'][$i]['module'];
					
						//Does this dependency exist at all?
						if (isset($module_list[$one_dep['name']]))
						{
							//Make sure we process any dependencies first
							self::check_dependencies_for_module($one_dep['name'], $module_list);
						
							//Now that it's processed, check for active and installed_version stuff
							if ($module_list[$one_dep['name']]['active'] == false)
							{
								//var_dump('parent is not active: ' . $one_dep['name'] . ' from ' . $module_name);
								$module_list[$module_name]['meets_dependencies'] = false;
								$module_list[$module_name]['active'] = false;
							}
							else if (isset($one_dep['minimum_version']) && version_compare($one_dep['minimum_version'], $module_list[$one_dep['name']]['installed_version'], '>'))
							{
								//var_dump('does not meet minimum version: ' . $one_dep['name'] . ' from ' . $module_name);
								$module_list[$module_name]['meets_dependencies'] = false;
								$module_list[$module_name]['active'] = false;
							}
						}
						else
						{
							//var_dump('does not exist: ' . $one_dep['name']);
							$module_list[$module_name]['meets_dependencies'] = false;
							$module_list[$module_name]['active'] = false;
						}
					}
				}
			}
		}
	}
	
	public static function get_module_class($name)
	{
		if (self::$module_list != null)
		{
			if (isset(self::$module_list[$name]) && self::$module_list[$name]['active'] == true)
			{
				if (isset(self::$module_list[$name]['object']))
				{
					return self::$module_list[$name]['object'];
				}
				else
				{
					if (class_exists($name) && is_subclass_of($name, 'CmsModuleBase'))
					{
						self::$module_list[$name]['object'] = new $name();
						return self::$module_list[$name]['object'];
					}
				}
			}
		}
		
		return null;
	}
	
	public static function is_installed($name)
	{
		if (isset(self::$module_list[$name]) && isset(self::$module_list[$name]['installed']))
			return self::$module_list[$name]['installed'];
		return false;
	}
	
	public static function is_active($name)
	{
		if (isset(self::$module_list[$name]) && isset(self::$module_list[$name]['active']))
			return self::$module_list[$name]['active'];
		return false;
	}
	
	public static function get_installed_module_details()
	{
		$db_prefix = db_prefix();
		return db()->GetAll("select * from {$db_prefix}modules");
	}
	
	public static function inject_installed_data_for_module($module_data, $installed_data)
	{
		$module_data['installed'] = false;
		$module_data['active'] = false;
		$module_data['installed_version'] = $module_data['version'];
		$module_data['needs_upgrade'] = false;
		$module_data['meets_minimum_core'] = true;
		
		foreach($installed_data as $one_row)
		{
			if ($one_row['module_name'] == $module_data['name'])
			{
				$module_data['installed'] = true;
				$module_data['active'] = ($one_row['active'] == '1' ? true : false);
				$module_data['installed_version'] = $one_row['version'];
				$module_data['needs_upgrade'] = version_compare($module_data['installed_version'], $one_row['version'], '<');
			}
		}
		
		return $module_data;
	}
	
	public static function xmlify_module_info_file($file)
	{
		$xml = simplexml_load_file($file);
		return self::convert_xml_to_array($xml);
	}
	
	public static function convert_xml_to_array($xml)
	{
		if (!($xml->children()))
		{
			return (string) $xml;
		}
		
		foreach ($xml->children() as $child)
		{
			$name = $child->getName();
			if (count($xml->$name) == 1)
			{
				$element[$name] = self::convert_xml_to_array($child);
			}
			else
			{
				$element[][$name] = self::convert_xml_to_array($child);
			}
		}
		
		return $element;
	}
	
	public static function find_module_info_files()
	{
		$filelist = array();
		
		$dir = join_path(ROOT_DIR, 'modules');
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false)
				{
					if ($file != '.' && $file != '..')
					{
						$mod_dir = join_path($dir, $file);
						if (is_dir($mod_dir))
						{
							$mod_info_file = join_path($dir, $file, $file . '.info.xml');
							if (is_file($mod_info_file) && is_readable($mod_info_file))
							{
								$filelist[] = $mod_info_file;
							}
						}
					}
				}
				closedir($dh);
			}
		}
		
		return $filelist;
	}
}

# vim:ts=4 sw=4 noet
?>