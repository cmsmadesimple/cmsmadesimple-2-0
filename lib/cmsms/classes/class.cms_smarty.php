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

/**
 * Represents a template in the database.
 *
 * @author Ted Kulp
 * @since 2.0
 **/
class CmsSmarty extends SilkSmarty
{
	function __construct()
	{
		parent::__construct();
		$this->register_resource("template", array(&$this, "template_get_template",
					"template_get_timestamp",
					"db_get_secure",
					"db_get_trusted"));
	}
	
	function template_get_template($tpl_name, &$tpl_source, &$smarty_obj)
	{
		$template = orm('CmsTemplate')->find_by_id($tpl_name);
		if ($template)
		{
			$tpl_source = $template->content;
			return true;
		}
		return false;
	}

	function template_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
	{
		$template = orm('CmsTemplate')->find_by_id($tpl_name);
		if ($template)
		{
			$tpl_timestamp = $template->modified_date->timestamp();
			return true;
		}
		return false;
	}
	
	function db_get_secure($tpl_name, &$smarty_obj)
	{
		// assume all templates are secure
		return true;
	}

	function db_get_trusted($tpl_name, &$smarty_obj)
	{
		// not used for templates
	}
}

?>