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

class PageController extends AdminController
{
	function index($params)
	{
		$this->set('tree', CmsPageTree::get_instance());
	}
	
	function main_content($params)
	{
		$page_id = substr($params['node_id'], 5);
		$page = orm('CmsPage')->find_by_id($page_id);
		$this->set('page', $page);
		$ajax = new SilkAjax();
		$ajax->replace_html('#main_section', $this->render_partial('main_section.tpl'));
		$ajax->script('reset_main_content();');
		return $ajax->get_result();
	}
	
	function check_unique_alias($params)
	{
		$ajax = new SilkAjax();
		$count = orm('CmsPage')->find_count(array('conditions' => array('unique_alias = ? AND id != ?', $params['alias'], $params['page_id'])));
		if ($params['alias'] == '')
		{
			$ajax->replace_html('#unique_alias_ok', 'Empty');
			$ajax->script('$("#unique_alias_ok").attr("style", "color: red;")');
		}
		else if ($count > 0 || $params['alias'] == '')
		{
			$ajax->replace_html('#unique_alias_ok', 'Used');
			$ajax->script('$("#unique_alias_ok").attr("style", "color: red;")');
		}
		else
		{
			$ajax->replace_html('#unique_alias_ok', 'Ok');
			$ajax->script('$("#unique_alias_ok").attr("style", "color: green;")');
		}
		$ajax->script('reset_main_content();');
		return $ajax->get_result();
	}
	
	function check_alias($params)
	{
		$ajax = new SilkAjax();
		$count = orm('CmsPage')->find_count(array('conditions' => array('alias = ? AND id != ?', $params['alias'], $params['page_id'])));
		if ($params['alias'] == '')
		{
			$ajax->replace_html('#alias_ok', 'Empty');
			$ajax->script('$("#alias_ok").attr("style", "color: red;")');
		}
		else if ($count > 0 || $params['alias'] == '')
		{
			$ajax->replace_html('#alias_ok', 'Used');
			$ajax->script('$("#alias_ok").attr("style", "color: red;")');
		}
		else
		{
			$ajax->replace_html('#alias_ok', 'Ok');
			$ajax->script('$("#alias_ok").attr("style", "color: green;")');
		}
		$ajax->script('reset_main_content();');
		return $ajax->get_result();
	}
}

# vim:ts=4 sw=4 noet
?>