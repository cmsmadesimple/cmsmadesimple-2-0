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

class PageTemplateController extends AdminController
{
	function index($params)
	{
		$templates = orm('CmsTemplate')->find_all();
		$this->set('templates', $templates);
	}
	
	function add($params)
	{
		if ($params['cancel'])
		{
			SilkResponse::redirect_to_action(array('controller' => 'page_template', 'action' => 'index'));
		}
		
		$template = new CmsTemplate();

		if ($params['submit'])
		{
			$template->update_parameters($params['template']);
			if ($template->save())
			{
				$this->flash = 'Template Added';
				SilkResponse::redirect_to_action(array('controller' => 'page_template', 'action' => 'index'));
			}
		}
		$this->set('template', $template);
	}
	
	function edit($params)
	{
		if ($params['cancel'] or !$params['id'])
		{
			SilkResponse::redirect_to_action(array('controller' => 'page_template', 'action' => 'index'));
		}

		$template = orm('cms_template')->find_by_id($params['id']);

		if ($params['submit'])
		{
			$template->update_parameters($params['template']);
			if ($template->save())
			{
				$this->flash = 'Template Updated';
				SilkResponse::redirect_to_action(array('controller' => 'page_template', 'action' => 'index'));
			}
		}
		$this->set('template', $template);
	}
	
	function delete($params)
	{
		$this->flash = orm('CmsTemplate')->delete($params['id']) ? 'Template Deleted' : 'There was an error deleteing the template';
		SilkResponse::redirect_to_action(array('controller' => 'page_template', 'action' => 'index'));
	}
}

# vim:ts=4 sw=4 noet
?>