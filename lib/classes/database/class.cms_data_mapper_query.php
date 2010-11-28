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

#########################################################
# Parts of this code are based/taken from phpDataMapper #
# Homepage: http://phpdatamapper.com/                   #
# Released under the MIT license                        #
#########################################################

class CmsDataMapperQuery extends CmsPdoQuery
{
	protected $_obj = null;
	
	public function __construct(CmsPdoDatabase $datasource, CmsDataMapper $obj)
	{
		$this->_datasource = $datasource;
		$this->_obj = $obj;
	}
	
	public function execute()
	{
		$ret = $this->_datasource->read($this);
		$classname = get_class($this->_obj);
		$result = array();
		
		if ($ret)
		{
			try
			{
				foreach ($ret as $one_row)
				{
					//Basically give before_load a chance to load that class type if necessary
					$newclassname = $classname;
					if ($this->_obj->get_type_field() != '' && isset($one_row[$this->_obj->get_type_field()]))
					{
						$newclassname = $one_row[$this->_obj->get_type_field()];
					}
			
					$this->_obj->before_load_caller($newclassname, $one_row);

					if (!($newclassname != $classname && class_exists($newclassname)))
					{
						$newclassname = $classname;
					}

					$oneobj = $this->_obj->instantiate_class($newclassname, $one_row);
					$oneobj = $this->_obj->fill_object($one_row, $oneobj);
					$result[] = $oneobj;
				}
			}
			catch (Exception $e)
			{
				//Nothing again
			}
		}
		
		return count($result) == 1 ? $result[0] : $result;
	}
}

# vim:ts=4 sw=4 noet