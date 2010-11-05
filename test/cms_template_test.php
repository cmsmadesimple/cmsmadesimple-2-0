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

require_once(dirname(dirname(__FILE__)) . '/lib/cmsms.api.php');

class CmsTemplateTest extends CmsTestCase
{
	public function setUp()
	{
		CmsCache::clear();
	}
	
	public function tearDown()
	{
		$this->setUp();
	}
	
	public function testGetPageBlocks()
	{
		$tpl = new CmsTemplate();
		
		$tpl->name = 'Test';
		$tpl->content = "{content name='thing'} {content} {content name='doo \"dad\"'}";
		$blocks = $tpl->get_page_blocks();
		$this->assertEqual(3, count($blocks));
		$this->assertTrue(array_key_exists('thing', $blocks));
		$this->assertTrue(array_key_exists('default', $blocks));
		$this->assertTrue(array_key_exists('doo "dad"', $blocks));
		$tpl->content = "{content name=\"bob's block\"} {content name=\"'joes' block\"}";
		$blocks = $tpl->get_page_blocks();
		$this->assertEqual(2, count($blocks));
		$this->assertTrue(array_key_exists("bob's block", $blocks));
		$this->assertTrue(array_key_exists("'joes' block", $blocks));
	}
}

# vim:ts=4 sw=4 noet