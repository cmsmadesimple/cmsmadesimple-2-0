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

require_once(dirname(dirname(__FILE__)) . '/lib/cmsms.api.php');

class CmsAdminLoginTest extends CmsWebTestCase
{
	public $_users = array();
	
	public function setUp()
	{
		CmsCache::clear();
	}
	
	public function tearDown()
	{
		while ($one_user = array_shift($this->_users))
		{
			$this->delete_user($one_user);
		}
		$this->setUp();
	}
	
	function testWeShouldNotBeAbleToSeeStuffIfNotLoggedIn()
	{
		$base = $this->get_admin_url_base();
		$this->setMaximumRedirects(0);
		$this->get($base . '/listcontent.php');
		$this->assertResponse(array(301, 302, 303, 307));
		$this->setMaximumRedirects(3);
		$this->get($base . '/listcontent.php');
		$this->assertPattern('/login.php/', $this->getUrl());
	}
	
	function testLogin()
	{
		$this->setMaximumRedirects(3);
		
		$user = $this->create_user();
		$this->_users[] = $user;
		
		$base = $this->get_admin_url_base();
		$this->get($base . '/login.php');
		
		//Test empty fields
		$this->assertField('username', '');
		$this->assertField('password', '');
		
		$this->click('Submit');
		$this->assertEqual($base . '/login.php', $this->getUrl());
		
		//Test bad username
		$this->get($base . '/login.php');
		
		$this->setField('username', $this->random_string());
		$this->setField('password', $this->random_string());
		
		$this->click('Submit');
		$this->assertEqual($base . '/login.php', $this->getUrl());
		
		//Test bad password
		$this->get($base . '/login.php');
		
		$this->setField('username', $user->name);
		$this->setField('password', $this->random_string());
		
		$this->click('Submit');
		$this->assertEqual($base . '/login.php', $this->getUrl());
		
		//Test correct password
		$this->get($base . '/login.php');
		
		$this->setField('username', $user->name);
		$this->setField('password', $user->decoded_password);
		
		$this->click('Submit');
		var_dump($this->getUrl());
		$this->assertEqual($base . '/index.php', $this->getUrl());
	}
}

# vim:ts=4 sw=4 noet