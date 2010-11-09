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

class CmsPdoTest extends CmsTestCase
{
	public function setUp()
	{
		$this->tearDown();
		$pdo = CmsPdoDatabase::get_instance();
		
		$pdo->create_table('test_orm_table',
			array(
				'id' => array(
					'type' => 'int',
					'primary' => true,
					'serial' => true,
				),
				'test_field' => array(
					'type' => 'string',
					'length' => 255,
					'required' => true,
				),
				'another_test_field' => array(
					'type' => 'string',
					'length' => 255,
				),
				'some_int' => array(
					'type' => 'int',
				),
				'some_float' => array(
					'type' => 'float',
				),
				'version' => array(
					'type' => 'int',
				),
				'create_date' => array(
					'type' => 'create_date',
				),
				'modified_date' => array(
					'type' => 'modified_date',
				),
			)
		);
		
		$pdo->execute("INSERT INTO {test_orm_table} (test_field, another_test_field, some_int, some_float, create_date, modified_date) VALUES ('test', 'blah', 5, 5.501, now() - 10, now() - 10)");
		$pdo->execute("INSERT INTO {test_orm_table} (test_field, create_date, modified_date) VALUES ('test2', now(), now())");
		$pdo->execute("INSERT INTO {test_orm_table} (test_field, create_date, modified_date) VALUES ('test3', now(), now())");
		
		$pdo->create_table('test_orm_table_child',
			array(
				'id' => array(
					'type' => 'int',
					'primary' => true,
					'serial' => true,
				),
				'parent_id' => array(
					'type' => 'int',
				),
				'some_other_field' => array(
					'type' => 'string',
					'length' => 255,
				),
				'version' => array(
					'type' => 'int',
				),
				'create_date' => array(
					'type' => 'create_date',
				),
				'modified_date' => array(
					'type' => 'modified_date',
				),
			)
		);
		
		$pdo->execute("INSERT INTO {test_orm_table_child} (parent_id, some_other_field, create_date, modified_date) VALUES (1, 'test', now(), now())");
	}
	
	public function tearDown()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$pdo->drop_table('test_orm_table_child');
		$pdo->drop_table('test_orm_table');
		CmsCache::clear();
	}
	
	public function testConnection()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$this->assertNotNull($pdo);
		$this->assertTrue(count($pdo->getAvailableDrivers()) > 0);
	}
	
	public function testAutoPrefix()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$this->assertEquals($pdo->query("SELECT * FROM " . cms_db_prefix() . 'content')->rowCount(), $pdo->query("SELECT * FROM {content}")->rowCount());
		$this->assertEquals($pdo->query("SELECT * FROM " . cms_db_prefix() . 'pages')->rowCount(), $pdo->query("SELECT * FROM {pages}")->rowCount());
	}
	
	public function testFetchAll()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$ret = $pdo->fetch_all('SELECT * FROM {content}');
		$this->assertTrue(is_array($ret));
		$this->assertEquals(count($ret) > 0);
		$this->assertTrue(isset($ret[0]['id']));
		$this->assertTrue(is_numeric($ret[0]['id']));
	}
	
	public function testFetchColumn()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$ret = $pdo->fetch_column('SELECT id FROM {content}');
		$this->assertTrue(is_array($ret));
		$this->assertEquals(count($ret) > 0);
		$this->assertTrue(is_numeric($ret[0]));
		
		$ret = $pdo->fetch_column('SELECT content_type, id FROM {content}', array(), 1);
		$this->assertTrue(is_array($ret));
		$this->assertEquals(count($ret) > 0);
		$this->assertTrue(is_numeric($ret[0]));
	}
	
	public function testGetOne()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$ret = $pdo->get_one('SELECT count(*) FROM {content}');
		$this->assertTrue(is_numeric($ret));
		$this->assertEquals($ret > 0);
		
		$ret = $pdo->get_one('SELECT id, content_type FROM {content}');
		$this->assertTrue(is_numeric($ret));
		$this->assertEquals($ret > 0);
	}
	
	public function testSelect()
	{
		$pdo = CmsPdoDatabase::get_instance();
		$row = $pdo->select("*")->from('{content}')->first();
		$this->assertNotNull($row);
		$this->assertNotEmpty($row);
		$this->assertTrue(is_numeric($row['id']));
		$this->assertFalse(is_numeric($row['title']));
		
		$row = $pdo->select("id, title")->from('{content}')->first();
		$this->assertNotNull($row);
		$this->assertNotEmpty($row);
		$this->assertTrue(is_numeric($row['id']));
		$this->assertFalse(is_numeric($row['title']));
		
		$row = $pdo->select(array('id', 'title'))->from('{content}')->first();
		$this->assertNotNull($row);
		$this->assertNotEmpty($row);
		$this->assertTrue(is_numeric($row['id']));
		$this->assertFalse(is_numeric($row['title']));
	}
	
	public function testCountAndCountSpl()
	{
		$pdo = CmsPdoDatabase::get_instance();
		
		//Tested earlier -- we know this works
		$fast_count = $pdo->get_one('SELECT count(*) FROM {content}');
		
		//Yes, we should be using count(*)
		$slow_count = $pdo->select("*")->from('{content}')->count();
		$this->assertNotNull($slow_count);
		$this->assertTrue(is_numeric($slow_count));
		$this->assertTrue($slow_count > 0);
		$this->assertTrue($fast_count == $slow_count);
		
		//Much quicker, but wordier
		$row = $pdo->select("count(*) as count")->from('{content}')->first();
		$this->assertNotNull($row);
		$this->assertNotEmpty($row);
		$this->assertTrue(is_numeric($row['count']));
		$this->assertTrue($row['count'] > 0);
		$this->assertTrue($row['count'] == $fast_count);
	}
	
	public function testIteratorSpl()
	{
		$pdo = CmsPdoDatabase::get_instance();
		
		$result = false;
		foreach ($pdo->select("*")->from('{content}') as $one_row)
		{
			$result = true;
		}
		
		$this->assertTrue($result);
	}
}

# vim:ts=4 sw=4 noet