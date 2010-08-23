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

class CmsErrorPageType extends CmsPage
{
	var $error_types;
	
	function __construct()
	{
		parent::__construct();
		$this->error_types = array('404' => lang('404description'));
	}
	
	function setup()
	{
		parent::setup();
		$this->remove_property('secure', 0);
		$this->remove_property('parent', -1);
		$this->remove_property('show_in_menu', false);
		$this->remove_property('menutext', '');
		$this->remove_property('target', '');
		$this->remove_property('extra1', '');
		$this->remove_property('extra2', '');
		$this->remove_property('extra3', '');
		$this->remove_property('image', '');
		$this->remove_property('thumbnail', '');
		$this->remove_property('accesskey', '');
		$this->remove_property('titleattribute', '');
		$this->remove_property('tabindex', '');
		$this->remove_property('active', true);
		$this->remove_property('cachable', false);
		$this->remove_property('alias', '');
		
		$this->add_property('error_code', 18, 'information');
	}
	
	function wants_children()
	{
		return false;
	}
	
	public function has_usable_link()
	{
		return false;
	}
	
	public function is_system_page()
	{
		return true;
	}
	
	public function is_viewable()
	{
		return true;
	}
	
	function friendly_name()
	{
		return $this->name;
	}
	
	function render()
	{
		if ($this->error_code)
		{
			$resp = CmsResponse::get_instance();
			$resp->set_status_code($this->error_code);
		}
		return parent::render();
	}
	
	function before_save()
	{
		$this->alias = 'system_error_num' . $this->error_code;
	}
	
	function display_single_element($one, $adding)
	{
		switch ($one)
		{
			case 'error_code':
			{
				$tmp = $this->error_types;
				foreach ($tmp as $key=>$val)
				{
					$tmp[$key] = "{$val} ({$key})";
				}
				return array(lang('error_type'), "{html_select name='page[error_code]' selected=\$page.error_code options=\$error_types}", array('error_types' => $tmp));
			}
			break;
			
			default:
				return parent::display_single_element($one, $adding);
		}
	}
}

# vim:ts=4 sw=4 noet
?>