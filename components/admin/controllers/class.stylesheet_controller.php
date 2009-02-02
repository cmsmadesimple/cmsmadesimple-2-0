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

class StylesheetController extends AdminController
{	
	function index($params)
	{
		$stylesheets = orm('CmsStylesheet')->find_all();
		$this->set('stylesheets', $stylesheets);
	}
	
	function add($params)
	{
		if ($params['cancel'])
		{
			SilkResponse::redirect_to_action(array('controller' => 'stylesheet', 'action' => 'index'));
		}
		
		$stylesheet = new CmsStylesheet();

		if ($params['submit'])
		{
			$stylesheet->update_parameters($params['stylesheet']);
			if ($stylesheet->save())
			{
				$this->flash = 'Stylesheet Added';
				SilkResponse::redirect_to_action(array('controller' => 'stylesheet', 'action' => 'index'));
			}
		}
		$this->set('stylesheet', $stylesheet);
	}
	
	function edit($params)
	{
		if ($params['cancel'] or !$params['id'])
		{
			SilkResponse::redirect_to_action(array('controller' => 'stylesheet', 'action' => 'index'));
		}

		$stylesheet = orm('CmsStylesheet')->find_by_id($params['id']);

		if ($params['submit'])
		{
			$stylesheet->update_parameters($params['stylesheet']);
			if ($stylesheet->save())
			{
				$this->flash = 'Stylesheet Updated';
				SilkResponse::redirect_to_action(array('controller' => 'stylesheet', 'action' => 'index'));
			}
		}
		$this->set('stylesheet', $stylesheet);
	}
	
	function delete($params)
	{
		$this->flash = orm('CmsStylesheet')->delete($params['id']) ? 'Stylesheet Deleted' : 'There was an error deleteing the stylesheet';
		SilkResponse::redirect_to_action(array('controller' => 'stylesheet', 'action' => 'index'));
	}
}

# vim:ts=4 sw=4 noet
?>