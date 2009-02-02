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

class AdminController extends SilkControllerBase
{	
	var $theme = 'default';
	
	function __construct()
	{
		parent::__construct();
		
		//Add a theme determination here
		
		$this->set_layout();
	}
	
	function set_layout($name = 'main')
	{
		$this->layout_name = join_path('themes', $this->theme, $name);
		$this->set('layout_root_url', SilkRequest::get_calculated_url_base(true) . '/layouts/themes/' . $this->theme);
		$this->set('layout_root_path', join_path(ROOT_DIR, 'layouts', 'themes', $this->theme));
		$this->set('silk_lib_root_url', SilkRequest::get_calculated_url_base(true) . '/lib/silk');
		$this->set('root_url', SilkRequest::get_calculated_url_base(true) . '/');
		
		//Setup the menu
		CmsAdminTree::fill_from_file();
		$this->set('root_node', CmsAdminTree::get_instance()->get_root_node());
	}
	
	function before_filter()
	{
		$this->check_access(SilkUserSession::is_logged_in());
	}
}

# vim:ts=4 sw=4 noet
?>