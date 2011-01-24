<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
// The MIT License
// 
// Copyright (c) 2008-2010 Ted Kulp
// 
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.

use \silk\test\TestFixture;

class UserFixture extends TestFixture
{
	var $model = '\cmsms\auth\User';
	var $records = array(
		array(
			'id' => 1,
			'username' => 'testuser',
			'password' => '',
			'first_name' => 'Test',
			'last_name' => 'User',
			'email' => 'test@test.com',
			'active' => true,
			'create_date' => '2010-01-01 15:00:00',
			'modified_date' => '2010-01-01 15:00:00',
		),
		array(
			'id' => 2,
			'username' => 'anotheruser',
			'password' => '',
			'first_name' => 'Another',
			'last_name' => 'User',
			'email' => 'test@another.net',
			'active' => false,
			'create_date' => '2010-01-01 15:00:00',
			'modified_date' => '2010-01-01 15:00:00',
		),
	);

	function __construct()
	{
		parent::__construct();
		$this->records[0]['password'] = md5('test');
		$this->records[1]['password'] = md5('blahblah');
	}
}

# vim:ts=4 sw=4 noet