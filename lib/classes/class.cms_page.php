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
#
#$Id$

define('CMS_CONTENT_HIDDEN_NAME','--------');


/**
 * Base content type class.  Extend this to create new content types for
 * the system.
 *
 * @author Ted Kulp
 * @since 2.0
 * @version $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license GPL
 **/
class CmsPage extends CmsObjectRelationalMapping
{
	var $table = 'pages';
	var $params = array('id' => -1, 'template_id' => -1, 'active' => true, 'default_content' => false, 'parent_id' => -1, 'lft' => 1, 'rgt' => 1, 'blocks' => array());
	var $field_maps = array('content_alias' => 'alias', 'titleattribute' => 'title_attribute', 'accesskey' => 'access_key', 'tabindex' => 'tab_index', 'content_name' => 'name');
	var $unused_fields = array();
	var $mProperties = array();

	var $props_loaded = false;
    var $mAdditionalUsers = array();
	var $addusers_loaded = false;
	
	#Stuff needed to do a doppleganger for CmsNode -- Multiple inheritence would rock right now
	var $tree = null;
	var $parentnode = null;
	var $children = array();
	
	var $_attributes = array();
	
	public function __construct()
	{
		parent::__construct();
		$template = CmsTemplate::get_default_template();
		if ($template)
		{
			$this->params['template_id'] = $template->id;
		}
	}
	
	public function setup($first_time = false)
	{
		$this->create_belongs_to_association('template', 'CmsTemplate', 'template_id');
		$this->assign_acts_as('NestedSet');
		//$this->assign_acts_as('Acl');
		
		$this->add_property('name', 1, 'information', true, true);
		$this->add_property('menu_text', 5, 'information', true, true);
		$this->add_property('template_id', 10, 'information', true, true);
		$this->add_property('parent', 15, 'information', true, true);
		$this->add_property('url_text', 20, 'information', true, true);
		$this->add_property('alias', 25, 'information', true, true);

		//$this->add_property('owner', 90);
		//$this->add_property('additionaleditors', 91);
		
		$this->add_property('active', 1, 'attributes');
		$this->add_property('show_in_menu', 5, 'attributes');
		$this->add_property('secure', 10, 'attributes');
		$this->add_property('cachable', 15, 'attributes');
		$this->add_property('pagedata_codeblock', 20, 'attributes');
		$this->add_property('target', 25, 'attributes');
		$this->add_property('image', 30, 'attributes');
		$this->add_property('thumbnail', 35, 'attributes');
		$this->add_property('titleattribute', 40, 'attributes');
		$this->add_property('accesskey', 45, 'attributes');
		$this->add_property('tabindex', 50, 'attributes');
		$this->add_property('disable_wysiwyg', 55, 'attributes');
		$this->add_property('extra1', 60, 'attributes');
		$this->add_property('extra2', 65, 'attributes');
		$this->add_property('extra3', 70, 'attributes');
		
		$this->add_property('metadata', 1, 'metadata');
	}
	
	/*
	function __sleep()
	{
		//This way if we serialize out to, say, a versioning piece,
		//all the properties are stored as well...
		$this->load_properties();
		return array_merge(parent::__sleep(), array('mProperties', 'props_loaded', 'preview', 'unused_fields', 'field_maps', 'table', 'params'));
	}
	*/
	
	//////////////////////////////////////////////////
	// methods that should be overridden
	//////////////////////////////////////////////////
	
	public function friendly_name()
	{
		stack_trace();
		die('ERROR: friendly_name not overriden');
	}

// 	public function get_type()
// 	{
// 		die('ERROR: type not overriden');
// 	}

    /**
     * Function content types to use to say whether or not they should show
     * up in lists where parents of content are set.  This will default to true,
     * but should be used in cases like Separator where you don't want it to 
     * have any children.
     * 
     * @since 0.11
     */
	public function wants_children()
	{
		return true;
	}

    /**
     * Should this link be used in various places where a link is the only
     * useful output?  (Like next/previous links in cms_selflink, for example)
     */
	public function has_usable_link()
	{
		return true;
	}

	public function is_default_possible()
	{
		return false;
	}

	public function is_copyable()
	{
		return true;
	}

	public function is_system_page()
	{
		return false;
	}

	public function is_previewable()
	{
		return false;
	}

	public function is_viewable()
	{
		return false;
	}

	/**
	 * Method to override for indexing content in the search index.  If this is not overridden,
	 * then the content type won't be searchable.
	 *
	 * @return void
	 * @author Ted Kulp
	 **/
	public function index()
	{
	}
	
	protected function validate()
	{
		$this->validate_numericality_of('parent_id',lang('invalidparent'));
		//$this->validate_not_blank('name', lang('nofieldgiven %s',array(lang('title'))));
		$this->validate_not_blank('menu_text', lang('nofieldgiven %s',array(lang('menutext'))));
		$result = self::validate_alias($this->alias, $this->id);
		if (!$result[0])  $this->add_validation_error($result[1]);
		$result = self::validate_url($this->url_text, $this->id, $this->parent_id);
		if (!$result[0])  $this->add_validation_error($result[1]);
		
	}
	
	static function validate_alias($alias, $id)
	{
		$count = cms_orm('CmsPage')->find_count(array('conditions' => array('content_alias = ? AND id <> ?', $alias, $id)));
		if ($alias == '')
		{
			return array(false, lang('nofieldgiven %s',array(lang('alias'))));
		}
		else if ($count > 0)
		{
			return array(false, lang('%s is already used',array(lang('alias'))));
		}
		else
		{
			return array(true, lang('ok'));
		}

	}

	static function validate_url($url_text, $id , $parent_id)
	{
		$count = cms_orm('CmsPage')->find_count(array('conditions' => array('url_text = ? AND id != ? and parent_id = ?', $url_text, $id, $parent_id)));
		if ($url_text == '')
		{
			return array(false, lang('nofieldgiven %s',array(lang('url_text'))));
		}
		else if ($count > 0 )
		{
			return array(false, lang('%s is already used',array(lang('url_text'))));
		}
		else
		{
			return array(true, lang('ok'));
		}

	}
 
	function friendly_hierarchy()
	{
		return CmsContentOperations::create_friendly_hierarchy_position($this->hierarchy());
	}
	
	function display()
	{
		cms_smarty()->assign_by_ref('current_page', $this);
		$template_content = $this->template->process();
		return $template_content;
	}
	
	function get_content_block($block_name = 'default', $can_return_new = false)
	{
		if (isset($this->blocks[$block_name]))
		{
			if (isset($this->blocks[$block_name]['id']))
			{
				$content = cms_orm('CmsContentBase')->find_by_id($this->blocks[$block_name]['id']);
				if ($content != null)
				{
					return $content;
				}
			}
		}
		
		if ($can_return_new)
		{
			$obj = CmsContentType::get_content_type_obj(CmsContentType::get_default_content_type()); //TODO: Make me check for allowed types on this block
			return $obj;
		}
		else
			return null;
	}
	
	function render_block($block_name = 'default')
	{
		$block = $this->get_content_block($block_name);
		if ($block != null)
		{
			return $block->render();
		}
		
		return '<!-- No content found for ' . $block_name . ' -->';
	}
	

	//////////////////////////////////////////////////
	// methods that do not need to be overridden
	//////////////////////////////////////////////////
	
	function after_load()
	{
		/*
		if (empty($this->content_props))
		{
			$val = cms_db()->GetAll("SELECT prop_name, content FROM {content_props} WHERE content_id = ?", array($this->id));
			if ($val)
			{
				foreach ($val as $row)
				{
					$this->set_property_value($row['prop_name'], $row['content']);
				}
			}
		}
		*/
	}
	
	protected function before_save()
	{
		//CmsEvents::send_event('Core', 'ContentEditPre', array('content' => &$this));
	}
	
	protected function after_save(&$result)
	{
		if ($result)
		{
			$concat = '';
			/*
			foreach ($this->mProperties as &$prop)
			{
				if ($prop->content_id != $this->id)
					$prop->content_id = $this->id;
				$prop->save();
				$concat .= $prop->content;
			}
			*/

			// save additional editors
			$users = cms_orm('CmsAdditionalEditor')->find_all_by_content_id($this->id);
			foreach( $users as &$user )
				{
					$user->delete();
				}
			foreach ($this->mAdditionalUsers as &$user)
			{
				if( $user->content_id != $this->id )
				{
					$user->content_id = $thid->id;
				}
				$user->save();
			}
		
			if ($concat != '')
				CmsContentOperations::do_cross_reference($this->id, 'content', $concat);
			
			CmsPage::set_all_hierarchy_positions(/*$this->lft, $this->rgt*/);
		
			//CmsEvents::send_event('Core', 'ContentEditPost', array('content' => &$this));
			CmsCache::clear();
		}
	}

	private function load_additional_users($force = false)
	{
		if( $force || !$this->addusers_loaded )
		{
			$users = cms_orm('CmsAdditionalEditor')->find_all_by_content_id($this->id);
			foreach ($users as &$user)
				{
					$this->mAdditionalUsers[] = $user;
				}
			$this->addusers_loaded = true;
		}
	}

	public function get_additional_users()
	{
		$this->load_additional_users();
		$result = array();
		foreach( $this->mAdditionalUsers as &$user )
		{
			$result[] = $user->user_id;
		}
		return $result;
	}

	public function set_additional_users($user_ids)
	{
		$this->load_additional_users();
		if( !is_array($user_ids) )
		{
			$this->mAdditionalUsers = array();
			return;
		}

		foreach( $user_ids as $one_user )
		{
			$found = false;
			foreach( $this->mAdditionalUsers as &$user )
			{
				if( $user->user_id == $one_user ) 
					{
						$found = true;
					}
			}
			if( !$found )
			{
				// create a new user
				$newuser = new CmsAdditionalEditor;
				$newuser->user_id = $one_user;
				$newuser->content_id = $this->id;
				$newuser->page_id = $this->id;
				$this->mAdditionalUsers[] = $newuser;
			}
		}
	}

    /**
     * Does this have children?
     */
	public function has_children()
	{
		return $this->rgt > ($this->lft + 1);
	}
	
	public function get_sibling_count()
	{
		if ($this->get_parent()->has_children())
		{
			return $this->get_parent()->get_children_count();
		}
		return 0;
	}
	
	public function shift_position($direction = 'up')
	{
		if ($direction == 'up')
			$this->move_up();
		else
			$this->move_down();
		
		CmsPage::set_all_hierarchy_positions();
		CmsCache::clear();
	}
	
    public function get_url($rewrite = true, $lang = '')
    {
		$config = cms_config();
		$url = "";
		$alias = ($this->alias != ''?$this->alias:$this->id);
		
		if ($this->default_content)
		{
			return $config['root_url'] . '/';
		}
		
		if ($config["assume_mod_rewrite"] && $rewrite == true)
		{
			$url = $config['root_url']. '/' . ($lang != '' ? "$lang/" : '') . $this->hierarchy_path() . (isset($config['page_extension'])?$config['page_extension']:'.html');
		}
		else
		{
		    if (isset($_SERVER['PHP_SELF']) && $config['internal_pretty_urls'] == true)
		    {
				$url = $config['root_url'] . '/index.php/' . ($lang != '' ? "$lang/" : '') . $this->hierarchy_path() . (isset($config['page_extension']) ? $config['page_extension'] : '.html');
		    }
		    else
		    {
				$url = $config['root_url'] . '/index.php?' . $config['query_var'] . '=' . $alias . ($lang != '' ? '&amp;lang=' . $lang : '');
		    }
		}
		return $url;
    }

	public function set_alias($alias = '')
	{
		$config = cms_config();

		$tolower = false;

		if ($alias == '' && $config['auto_alias_content'] == true)
		{
			/*
			$alias = trim($this->get_property_value('menu_text', CmsMultiLanguage::get_default_language()));
			if ($alias == '')
			{
			    $alias = trim($this->get_property_value('name', CmsMultiLanguage::get_default_language()));
			}
			*/
			$alias = trim($this->params['name']);
			if ($alias == '')
			{
				$alias = trim($this->params['menu_text']);
			}
			
			$tolower = true;
			$alias = munge_string_to_url($alias, $tolower);

			// Make sure auto-generated new alias is not already in use on a different page, if it does, add "-2" to the alias
			$error = cmsms()->GetContentOperations()->CheckAliasError($alias);
			if ($error !== FALSE)
			{
				if (FALSE == empty($alias))
				{
					$alias_num_add = 2;
					// If a '-2' version of the alias already exists
					// Check the '-3' version etc.
					while (cmsms()->GetContentOperations()->CheckAliasError($alias.'-'.$alias_num_add) !== FALSE)
					{
						$alias_num_add++;
					}
					$alias .= '-'.$alias_num_add;
				}
				else
				{
					$alias = '';
				}
			}
		}

		$this->params['alias'] = munge_string_to_url($alias, $tolower);
	}
	
	public function WantsChildren()
    {
		$this->wants_children();
    }
	
	protected function before_delete()
	{
		if ($this->has_children()) return false;
		if ($this->default_content()) return false;

		CmsEventManager::send_event('Core::ContentDeletePre', array('content' => &$this));
		return true;
	}
	
	protected function after_delete()
	{
		/*
		$items =& cms_orm('CmsContentProperty')->find_all_by_content_id($this->id);
		foreach ($items as &$item)
		{
			$item->delete();
		}
		*/
		
		if (isset($this->params['blocks']) && is_array($this->params['blocks']))
		{
			foreach ($this->params['blocks'] as $the_block)
			{
				if (isset($the_block['id']))
				{
					$obj = cms_orm('CmsContentBase')->load($the_block['id']);
					if ($obj)
					{
						$obj->delete();
					}
				}
			}
		}
		
		
		// delete all the additional editors.
		$users =& cms_orm('CmsAdditionalEditor')->find_all_by_content_id($this->id);
		foreach( $users as &$user )
		{
			$user->delete();
		}

		#Remove the cross references
		CmsContentOperations::remove_cross_references($this->id, 'content');

		CmsCache::clear();
		CmsPage::set_all_hierarchy_positions();

		CmsEventManager::send_event('Core::ContentDeletePost', array('content' => &$this));
		audit($this->id,$this->name(),'Deleted Content');
	}
	
	protected function instantiate_class($type, $row)
	{
		$ret = null;
		
		if (isset($row['page_type']) && $row['page_type'] != '')
			$ret = CmsPageType::get_page_type_class_by_type($row['page_type'], true);
		
		if ($ret != null)
			return $ret;
		else
			return parent::instantiate_class($type, $row);
	}
	
	public function template_name()
	{
		try
		{
			return cms_orm('CmsTemplate')->find_by_template_id($this->template_id)->name;
		}
		catch (Exception $e)
		{
			return '';
		}
	}
	
	/**
	 * Renders the given pageinfo object based on it's assigned content and template
	 * parts.
	 *
	 * @return string The rendered html
	 **/
	public function render()
	{
		return $this->display();
		/*
		$html = '';
		$cached = false;
		
		$gCms = cmsms();
		$smarty = cms_smarty();
		
		$id = cms_smarty()->id;

		#If this is a case where a module doesn't want a template to be shown, just disable caching
		if (isset($id) && $id != '' && isset($_REQUEST[$id.'showtemplate']) && ($_REQUEST[$id.'showtemplate'] == 'false' || $_REQUEST[$id.'showtemplate'] == false))
		{
			$html = $smarty->fetch('template:notemplate') . "\n";
		}
		else
		{
			//$smarty->caching = false;
			//$smarty->compile_check = true;
			($smarty->is_cached('template:'.$this->template_id)?$cached="":$cached="not ");
			// Added HTTP_HOST here to work with multisites - SK
			$html = $smarty->fetch('template:'.$this->template_id) . "\n";
			if (isset($_REQUEST['tmpfile']))
			{
				$smarty->clear_compiled_tpl('template:'.$this->template_id);
			}
		}

		if (!$cached)
		{
			Events::SendEvent('Core', 'ContentPostRenderNonCached', array(&$html));
		}

		Events::SendEvent('Core', 'ContentPostRender', array('content' => &$html));
		
		return $html;
		*/
	}
	
	public function add_child($node)
	{
		$node->set_parent($this);
		$node->tree = $this->tree;
		$this->children[] = $node;
	}
	
	public function get_tree()
	{
		return $this->tree;
	}
	
	public function depth()
	{
		if ($this->hierarchy)
		{
			return count(explode('.', $this->hierarchy)) - 1;
		}
		else
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
	}
	
	public function get_level()
	{
		return $this->depth();
	}
	
	// note this is not the parent_id
	public function get_parent()
	{
		return $this->parentnode;
	}

	// note this is not the parent_id
	public function set_parent($node)
	{
		$this->parentnode = $node;
	}
	
	public function get_children_count()
	{
		return count($this->children);
	}

	// not needed?
	public function getChildrenCount()
	{
		return $this->get_children_count();
	}

	public function &get_children()
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
	
    public function &get_flat_list()
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

	/**
	 * Backwards compatiblity function.  Just returns $this.
	 *
	 * @return object Returns $this
	 * @author Ted Kulp
	 */
	public function get_content()
	{
		return $this;
	}

	public function check_edit_permission($uid)
	{
		if( $uid == $this->owner_id ) return true;
		$addteditors = $this->get_additional_users();
		if( in_array($uid,$addteditors) ) return true;
		return false;
	}
	
	public static function get_all_pages($loadprops = true)
	{
		return cms_orm('CmsPage')->find_all(array('order' => 'lft ASC'));
	}
	
	public static function set_all_hierarchy_positions($lft = -1, $rgt = -1)
	{
		$gCms = cmsms();
		$db = cms_db();

		if ($lft > -1)
		{
			if ($rgt > -1)
				$query = "SELECT id FROM ".cms_db_prefix()."pages WHERE lft >= " . $db->qstr($lft) . ' AND rgt <= ' . $db->qstr($rgt);
			else
				$query = "SELECT id FROM ".cms_db_prefix()."pages WHERE lft >= " . $db->qstr($lft);
		}
		else
			$query = "SELECT id FROM ".cms_db_prefix()."pages";

		$dbresult = array();
		//try
		//{
			$dbresult = $db->GetCol($query);
		//}
		//catch (Exception $ex)
		//{
		
		//}
		
		foreach ($dbresult as $one_id)
		{
			self::set_hierarchy_position($one_id);
		}
	}
	
    /**
     * Updates the hierarchy position of one item
     */
	public static function set_hierarchy_position($page_id)
	{
		$gCms = cmsms();
		$db = cms_db();

		$current_hierarchy_position = '';
		$current_id_hierarchy_position = '';
		$current_hierarchy_path = '';
		$current_parent_id = $page_id;
		$count = 0;

		while ($current_parent_id > -1)
		{
			$query = "SELECT item_order, parent_id, url_text FROM ".cms_db_prefix()."pages WHERE id = ?";
			$row = $db->GetRow($query, array($current_parent_id));
			if ($row)
			{
				$current_hierarchy_position = $row['item_order'] . '.' . $current_hierarchy_position;
				$current_id_hierarchy_position = $current_parent_id . '.' . $current_id_hierarchy_position;
				$current_hierarchy_path = $row['url_text'] . '/' . $current_hierarchy_path;
				$current_parent_id = $row['parent_id'];
				$count++;
			}
			else
			{
				$current_parent_id = 1;
			}
		}

		if (strlen($current_hierarchy_position) > 0)
		{
			$current_hierarchy_position = substr($current_hierarchy_position, 0, strlen($current_hierarchy_position) - 1);
		}
		if (strlen($current_id_hierarchy_position) > 0)
		{
			$current_id_hierarchy_position = substr($current_id_hierarchy_position, 0, strlen($current_id_hierarchy_position) - 1);
		}
		if (strlen($current_hierarchy_path) > 0)
		{
			$current_hierarchy_path = substr($current_hierarchy_path, 0, strlen($current_hierarchy_path) - 1);
		}

		//debug_buffer(array($current_hierarchy_position, $current_id_hierarchy_position, implode(',', $prop_name_array), $page_id));

		$query = "UPDATE {pages} SET hierarchy = ?, id_hierarchy = ?, hierarchy_path = ? WHERE id = ?";
		$db->Execute($query, array($current_hierarchy_position, $current_id_hierarchy_position, $current_hierarchy_path, $page_id));
	}
	
	public static function create_hierarchy_dropdown($current = '', $parent = '', $name = 'parent_id', $addt_content = '')
	{
		$result = '<select name="'.$name.'"';
		if ($addt_content != '')
		{
			$result .= ' ' . $addt_content;
		}
		$result .= '>';
		$result .= '<option value="-1">None</option>';

		$allcontent = CmsContentOperations::get_all_content(false);

		if ($allcontent !== FALSE && count($allcontent) > 0)
		{
			$curhierarchy = '';

			foreach ($allcontent as $one)
			{
				if ($one->id == $current)
				{
					#Grab hierarchy just in case we need to check children
					#(which will always be after)
					$curhierarchy = $one->hierarchy;

					#Then jump out.  We don't want ourselves in the list.
					continue;
				}

				#If it's a child of the current, we don't want to show it as it
				#could cause a deadlock.
				if ($curhierarchy != '' && strstr($one->hierarchy . '.', $curhierarchy . '.') == $one->hierarchy . '.')
				{
					continue;
				}

				#Don't include content types that do not want children either...
				if (!$one->wants_children())
				{
					continue;
				}

				$result .= '<option value="' . $one->id . '"';
                #Select current parent if it exists
				if ($one->id == $parent)
				{
					$result .= ' selected="selected"';
				}
				$result .= '>'.$one->hierarchy.'. - '.$one->name['en_US'].'</option>';
			}
		}

		$result .= '</select>';

		return $result;
	}
	
	function add_property($name, $priority, $tab = 'attributes', $is_required = false, $has_javascript = false)
	{
		if (!is_array($this->_attributes))
		{
			$this->_attributes = array();
		}

		$this->_attributes[] = array($name, $priority, $tab, $is_required, $has_javascript);
	}
	
	function remove_property($name, $default_value)
	{
		if (!is_array($this->_attributes)) return;
		
		$tmp = array();
		for ($i = 0; $i < count($this->_attributes); $i++)
		{
			if (is_array($this->_attributes[$i]) && $this->_attributes[$i][0] == $name)
			{
				continue;
			}
			$tmp[] = $this->_attributes[$i];
		}
		$this->_attributes = $tmp;
	}
	
	function has_property($name)
	{
		$tmp = array();
		
		foreach ($this->_attributes as $one)
		{
			$tmp[] = $one[0];
		}
		
		return in_array($name, $tmp);
	}
	
	protected function sort_attributes_by_priority($a, $b)
	{
		if ($a[1] < $b[1])
			return -1;
		else if ($a[1] == $b[1])
			return 0;
		else
			return 1;
	}
	
	function display_attributes($tab_name = '', $adding = false, $negative = false, $javascript = false)
	{
		$attrs = array();
		
		if ($tab_name != '')
		{
			foreach ($this->_attributes as $one)
			{
				if ($one[2] == $tab_name)
					$attrs[] = $one;
			}
		}
		else
		{
			$attrs = $this->_attributes;
		}
		
		// get our required attributes
		/*
		$basic_attributes = array();
		foreach ($this->_attributes as $one)
		{
			if ($one[3] == true)
				$basic_attributes[] = $one;
		}
		*/
		
		// remove any duplicates
		$tmp = array();
		foreach ($attrs as $one)
		{
			$found = 0;
			foreach ($tmp as $t1)
			{
				if ($one[0] == $t1[0])
				{
					$found = 1;
					break;
				}
			}
			if (!$found)
			{
				$tmp[] = $one;
			}
		}
		$attrs = $tmp;
		
		usort($attrs, array($this, 'sort_attributes_by_priority'));
		
		$tmp = $this->display_admin_attributes($attrs, $adding, $javascript);
		return $tmp;
	}
	
	function display_admin_attributes($attribute_list, $adding = false, $javascript = false)
	{
		// sort the attributes
		$ret = array();
		
		foreach ($attribute_list as $one)
		{
			$val = null;
			
			if ($javascript)
			{
				if ($one[4] == true)
				{
					$val = $this->display_javascript($one[0], $adding);
				}
			}
			else
			{
				$val = $this->display_single_element($one[0], $adding);
			}
			
			if ($val)
			{
				$ret[] = $val;
			}
		}
		
		return $ret;
	}
	
	function display_single_element($element_name, $adding)
	{
		switch ($element_name)
		{
			case 'cachable':
			{
				return array(lang('cachable'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
			}
			break;

			case 'name':
			{
				return array(lang('title'), "{html_input name='page[{$element_name}][en_US]' value=\$page.{$element_name}.en_US}");
			}
			break;

			case 'menu_text':
			{
				return array(lang('menutext'), "{html_input name='page[{$element_name}][en_US]' value=\$page.{$element_name}.en_US}");
			}
			break;

			case 'parent':
			{
				$parent_dropdown = $this->create_hierarchy_dropdown($this->id, $this->parent_id, 'page[parent_id]', 'id="parent_dropdown"');
				return array(lang('parent'), "{\$parent_dropdown}", array('parent_dropdown' => $parent_dropdown));
			}
			break;

			case 'active':
			{
				if ($this->default_content == false)
				{
					return array(lang('active'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
				}
			}
			break;

			case 'show_in_menu':
			{
				return array(lang('showinmenu'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
			}
			break;

			case 'target':
			{
				return array(lang('target'), "{html_select name='page[{$element_name}]' selected=\$page.{$element_name} options=['---'=>'none','_blank'=>'_blank','_parent'=>'_parent','_self'=>'_self','_top'=>'_top']}");
			}
			break;

			case 'alias':
			{
				return array(lang('pagealias'), "{html_input html_id='alias' name='page[alias]' autocomplete='off' value=\$page.alias}&nbsp;&nbsp;<span id=\"alias_ok\" style=\"color: green;\">Ok</span>");
			}
			break;
			
			case 'url_text':
			{
				return array(lang('url_text'), "<span id=\"parent_path\">{\$parent_path}</span>{html_input html_id='url_text' name='page[url_text]' autocomplete='off' value=\$page.url_text}&nbsp;&nbsp;<span id=\"url_text_ok\" style=\"color: green;\">Ok</span>");
			}
			break;

			case 'secure':
			{
				return array(lang('secure_page'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
			}
			break;

			case 'image':
			{
				/*
				$dir = $config['image_uploads_path'];
				$data = $this->GetPropertyValue('image');
				$dropdown = create_file_dropdown('image',$dir,$data,'jpg,jpeg,png,gif','',true,'','thumb_');
				return array(lang('image').':',$dropdown);
				*/
			}
			break;

			case 'thumbnail':
			{
				/*
				$dir = $config['image_uploads_path'];
				$data = $this->GetPropertyValue('thumbnail');
				$dropdown = create_file_dropdown('thumbnail',$dir,$data,'jpg,jpeg,png,gif','',true,'','thumb_',0);
				return array(lang('thumbnail').':',$dropdown);
				*/
			}
			break;

			case 'titleattribute':
			{
				return array(lang('titleattribute'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'accesskey':
			{
				return array(lang('accesskey'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'tabindex':
			{
				return array(lang('tabindex'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'extra1':
			{
				return array(lang('extra1'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'extra2':
			{
				return array(lang('extra2'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'extra3':
			{
				return array(lang('extra3'), "{html_input name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'owner':
			{
				$showadmin = check_ownership(get_userid(), $this->Id());
				$userops =& $gCms->GetUserOperations();
				if (!$adding && $showadmin)
				{
					return array(lang('owner').':', $userops->GenerateDropdown($this->Owner()));
				}
			}
			break;

			case 'additionaleditors':
			{
				// do owner/additional-editor stuff
				if( $adding || check_ownership(get_userid(),$this->Id()) )
				{
					return $this->ShowAdditionalEditors();
				}
			}
			break;
			
			case 'template_id':
			{
				return array(lang('template'), "{html_options name='page[template_id]' id='template_id' options=\$template_items selected=\$page.template_id}");
			}
			break;

			case 'metadata':
			{
				return array(lang('page_metadata'), "{html_textarea name='page[{$element_name}]' value=\$page.{$element_name}}");
			}
			break;

			case 'pagedata_codeblock':
			{
				return array(lang('pagedata_codeblock'), "{html_textarea name='page[pagedata]' value=\$page.pagedata}");
			}
			break;

			case 'searchable':
			{
				return array(lang('searchable'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
			}
			break;

			case 'disable_wysiwyg':
			{
				return array(lang('disable_wysiwyg'), "{html_checkbox name='page[{$element_name}]' selected=\$page.{$element_name}}");
			}
			break;

			default:
			{
				//stack_trace();
				//die('unknown property '.$one);
			}
		}
		
		return null;
	}
	
	function display_javascript($element_name, $adding)
	{
		switch ($element_name)
		{
			case 'alias':
			{
				return "
					\$('#alias').delayedObserver(function()
					{
						cms_ajax_check_alias({name:'alias', value:\$('#alias').val()}, {name:'page_id', value:\$('#page_id').html()}, {name:'serialized_page', value:\$('#serialized_page').val()});
					}, 0.5);
				";
			}
			break;
			
			case 'url_text':
			{
				return "
					\$('#url_text').delayedObserver(function()
					{
						cms_ajax_check_url({name:'alias', value:\$('#url_text').val()}, {name:'page_id', value:\$('#page_id').html()}, {name:'parent_id', value:\$('#parent_dropdown').val()}, {name:'serialized_page', value:\$('#serialized_page').val()});
					}, 0.5);
				";
			}
			break;
			
			case 'parent':
			{
				return "
					\$('#parent_dropdown').change(function()
					{
						var args = [{name:'parent_id', value:\$('#parent_dropdown').val()}, {name:'page_id', value:\$('#page_id').html()}, {name:'serialized_page', value:\$('#serialized_page').val()}];
						cms_ajax_call('change_parent', args, {
							success: function (data, textStatus) {
								cms_ajax_callback(data);
								cms_ajax_check_url({name:'alias', value:\$('#url_text').val()}, {name:'page_id', value:\$('#page_id').html()}, {name:'parent_id', value:\$('#parent_dropdown').val()}, {name:'serialized_page', value:\$('#serialized_page').val()});
							}
						});
					});
				";
			}
			break;
			
			case 'template_id':
			{
				return "
					\$('#template_id').change(function()
					{
						cms_ajax_change_template(\$('#content_form').serializeForCmsAjax());
					});
				";
			}
			break;
			
			default:
			{
				//stack_trace();
				//die('unknown property '.$one);
			}
		}
	}
}


?>
