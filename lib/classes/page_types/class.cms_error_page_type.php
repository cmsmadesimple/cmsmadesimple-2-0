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
	function __construct()
	{
		parent::__construct();
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
		return parent::render();
	}
}

# vim:ts=4 sw=4 noet
?>