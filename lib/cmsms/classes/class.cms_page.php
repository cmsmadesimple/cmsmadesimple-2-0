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

/**
 * Base page class.  Extend this to create new content types for
 * the system.
 *
 * @author Ted Kulp
 * @since 2.0
 **/
class CmsPage extends SilkObjectRelationalMapping
{
	var $table = 'pages';
	var $params = array('id' => -1, 'template_id' => -1, 'active' => true, 'default_content' => false, 'parent_id' => -1, 'lft' => 1, 'rgt' => 1, 'blocks' => array());
	
	#Stuff needed to do a doppleganger for CmsNode -- Multiple inheritence would rock right now
	var $tree = null;
	var $parentnode = null;
	var $children = array();
	
	function __construct()
	{
		parent::__construct();
	}
	
	function setup()
	{
		$this->create_belongs_to_association('template', 'CmsTemplate', 'template_id');
		$this->assign_acts_as('NestedSet');
	}
	
	function display()
	{
		smarty()->register_object('current_page', $this);
		$template_content = $this->template->process();
		return $template_content;
	}
	
	function get_content($block_name = 'default')
	{
		if (isset($this->blocks[$block_name]))
		{
			if (isset($this->blocks[$block_name]['id']))
			{
				$content = orm('CmsContentBase')->find_by_id($this->blocks[$block_name]['id']);
				if ($content != null)
				{
					return $content->get_content();
				}
			}
		}
		
		return '<!-- No content found for ' . $block_name . ' -->';
	}
	
    /**
     * Function content types to use to say whether or not they should show
     * up in lists where parents of content are set.  This will default to true,
     * but should be used in cases like Separator where you don't want it to 
     * have any children.
     * 
     * @since 0.11
     */
	function wants_children()
	{
		return true;
	}
	
	function can_preview()
	{
		return false;
	}
	
	function template_name()
	{
		$tmp = $this->template;
		if ($tmp)
			return $tmp->name;
		return '';
	}
	
	function add_child($node)
	{
		$node->set_parent($this);
		$node->tree = $this->tree;
		$this->children[] = $node;
	}
	
	function get_tree()
	{
		return $this->tree;
	}
	
	function has_children()
	{
		return $this->rgt > $this->lft + 1;
	}
	
	function depth()
	{
		$depth = 0;
		$currLevel = &$this;

		while ($currLevel->parentnode)
		{
			$depth++;
			$currLevel = &$currLevel->parentnode;
		}
		
		return $depth;
	}
	
	function get_level()
	{
		return $this->depth();
	}
	
	function getLevel()
	{
		return $this->depth();
	}
	
	function get_parent()
	{
		return $this->parentnode;
	}
	
	function set_parent($node)
	{
		$this->parentnode = $node;
	}
	
	function get_children_count()
	{
		return count($this->children);
	}
	
	function getChildrenCount()
	{
		return $this->get_children_count();
	}

	function &get_children()
	{
		if ($this->has_children())
		{
			//We know there are children, but no nodes have been
			//created yet.  We should probably do that.
			if (!$this->children_loaded())
			{
				//$this->tree->load_child_nodes(-1, $this->lft, $this->rgt);
				$this->tree->load_child_nodes($this->id);
			}
		}
		return $this->children;
	}
	
    function &get_flat_list()
    {
		$return = array();
		
		if ($this->has_children())
		{
			for ($i=0; $i<count($this->children); $i++)
			{
				$return[] = &$this->children[$i];
				$return = array_merge($return, $this->children[$i]->get_flat_list());
			}
		}
        
        return $return;
    }

	function &getFlatList()
	{
		$tmp =& $this->get_flat_list();
		return $tmp;
	}
}

?>
