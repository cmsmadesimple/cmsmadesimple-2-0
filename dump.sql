-- MySQL dump 10.11
--
-- Host: localhost    Database: cms_innodb
-- ------------------------------------------------------
-- Server version	5.0.51a

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cms_additional_htmlblob_users`
--

DROP TABLE IF EXISTS `cms_additional_htmlblob_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_additional_htmlblob_users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `htmlblob_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_additional_htmlblob_users`
--

LOCK TABLES `cms_additional_htmlblob_users` WRITE;
/*!40000 ALTER TABLE `cms_additional_htmlblob_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_additional_htmlblob_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_admin_bookmarks`
--

DROP TABLE IF EXISTS `cms_admin_bookmarks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_admin_bookmarks` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `title` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_admin_bookmarks`
--

LOCK TABLES `cms_admin_bookmarks` WRITE;
/*!40000 ALTER TABLE `cms_admin_bookmarks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_admin_bookmarks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_admin_recent_pages`
--

DROP TABLE IF EXISTS `cms_admin_recent_pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_admin_recent_pages` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `title` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `access_time` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_admin_recent_pages`
--

LOCK TABLES `cms_admin_recent_pages` WRITE;
/*!40000 ALTER TABLE `cms_admin_recent_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_admin_recent_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_adminlog`
--

DROP TABLE IF EXISTS `cms_adminlog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_adminlog` (
  `timestamp` int(11) default NULL,
  `user_id` int(11) default NULL,
  `username` varchar(25) default NULL,
  `item_id` int(11) default NULL,
  `item_name` varchar(50) default NULL,
  `action` varchar(255) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_adminlog`
--

LOCK TABLES `cms_adminlog` WRITE;
/*!40000 ALTER TABLE `cms_adminlog` DISABLE KEYS */;
INSERT INTO `cms_adminlog` VALUES (1229288348,1,'',3,NULL,'Added Content');
/*!40000 ALTER TABLE `cms_adminlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_attribute_defns`
--

DROP TABLE IF EXISTS `cms_attribute_defns`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_attribute_defns` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(100) default NULL,
  `extra_attr` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `attribute_type` varchar(50) default NULL,
  `optional` text,
  `user_generated` tinyint(4) default '1',
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_attribute_defns`
--

LOCK TABLES `cms_attribute_defns` WRITE;
/*!40000 ALTER TABLE `cms_attribute_defns` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_attribute_defns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_attributes`
--

DROP TABLE IF EXISTS `cms_attributes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_attributes` (
  `id` int(11) NOT NULL auto_increment,
  `attribute_id` int(11) default NULL,
  `object_id` int(11) default NULL,
  `language` varchar(50) default NULL,
  `content` longtext,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_and_object` (`attribute_id`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_attributes`
--

LOCK TABLES `cms_attributes` WRITE;
/*!40000 ALTER TABLE `cms_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_blog_categories`
--

DROP TABLE IF EXISTS `cms_blog_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_blog_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `slug` varchar(255) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_blog_categories`
--

LOCK TABLES `cms_blog_categories` WRITE;
/*!40000 ALTER TABLE `cms_blog_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_blog_post_categories`
--

DROP TABLE IF EXISTS `cms_blog_post_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_blog_post_categories` (
  `category_id` int(11) default NULL,
  `post_id` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_blog_post_categories`
--

LOCK TABLES `cms_blog_post_categories` WRITE;
/*!40000 ALTER TABLE `cms_blog_post_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_blog_post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_blog_posts`
--

DROP TABLE IF EXISTS `cms_blog_posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_blog_posts` (
  `id` int(11) NOT NULL auto_increment,
  `author_id` int(11) default NULL,
  `post_date` datetime default NULL,
  `post_year` int(11) default NULL,
  `post_month` int(11) default NULL,
  `post_day` int(11) default NULL,
  `title` varchar(255) default NULL,
  `slug` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `content` longtext,
  `summary` longtext,
  `status` varchar(25) default NULL,
  `use_comments` int(1) default '1',
  `processor` varchar(25) default '',
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_blog_posts`
--

LOCK TABLES `cms_blog_posts` WRITE;
/*!40000 ALTER TABLE `cms_blog_posts` DISABLE KEYS */;
INSERT INTO `cms_blog_posts` VALUES (1,1,'2008-12-14 20:59:23',2008,12,14,'Test Post','test-post','2008/12/14/test-post','Test post','','publish',0,'none','2008-12-14 20:59:35','2008-12-14 20:59:35'),(2,1,'2008-12-14 20:59:23',2008,12,14,'Test Post','test-post','2008/12/14/test-post','Test post','And summary\r\n','publish',0,'none','2008-12-14 21:00:06','2008-12-14 21:01:22');
/*!40000 ALTER TABLE `cms_blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_content`
--

DROP TABLE IF EXISTS `cms_content`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_content` (
  `id` int(11) NOT NULL auto_increment,
  `content_name` varchar(255) default NULL,
  `type` varchar(25) default NULL,
  `owner_id` int(11) default NULL,
  `parent_id` int(11) default NULL,
  `template_id` int(11) default NULL,
  `item_order` int(11) default NULL,
  `lft` int(11) default NULL,
  `rgt` int(11) default NULL,
  `hierarchy` varchar(255) default NULL,
  `default_content` tinyint(4) default NULL,
  `menu_text` varchar(255) default NULL,
  `content_alias` varchar(255) default NULL,
  `show_in_menu` tinyint(4) default NULL,
  `collapsed` tinyint(4) default NULL,
  `markup` varchar(25) default NULL,
  `active` tinyint(4) default NULL,
  `cachable` tinyint(4) default NULL,
  `id_hierarchy` varchar(255) default NULL,
  `hierarchy_path` text,
  `prop_names` text,
  `metadata` text,
  `titleattribute` varchar(255) default NULL,
  `tabindex` varchar(10) default NULL,
  `accesskey` varchar(5) default NULL,
  `last_modified_by` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `alias_and_active` (`content_alias`,`active`),
  KEY `default_content` (`default_content`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_content`
--

LOCK TABLES `cms_content` WRITE;
/*!40000 ALTER TABLE `cms_content` DISABLE KEYS */;
INSERT INTO `cms_content` VALUES (1,'__root_node__','root',1,-1,-1,1,1,6,'',0,'__root_node__','__root_node__',0,0,'none',1,0,'','__root_node__','','','','','',1,NULL,NULL),(2,'','content',1,1,1,1,2,3,'1',1,'','',1,0,'',1,0,'2','','default-block-type,default-content,front_page_image-block-type,front_page_image-content,name,menu_text','','','','',1,'2007-11-20 17:49:26','2007-11-20 17:49:41'),(3,'','content',1,1,1,2,4,5,'2',0,'','blog',1,0,'',1,0,'3','blog','name,menu_text,default-block-type,default-content','','','','',1,'2008-12-14 20:59:08','2008-12-14 20:59:08');
/*!40000 ALTER TABLE `cms_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_content_props`
--

DROP TABLE IF EXISTS `cms_content_props`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_content_props` (
  `id` int(11) NOT NULL auto_increment,
  `content_id` int(11) default NULL,
  `type` varchar(25) default NULL,
  `prop_name` varchar(255) default NULL,
  `language` varchar(50) default NULL,
  `content` longtext,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_content_props`
--

LOCK TABLES `cms_content_props` WRITE;
/*!40000 ALTER TABLE `cms_content_props` DISABLE KEYS */;
INSERT INTO `cms_content_props` VALUES (1,2,'cmscontentproperty','default-block-type','en_US','html','2007-11-20 17:39:47','2007-11-20 18:07:27'),(2,2,'cmscontentproperty','default-content','en_US','<p>Congratulations! You now have a fully functional installation of CMS Made Simple and you are <em>almost</em> ready to start building your site. First thing though, you should click <a href=\"install/upgrade.php\" title=\"Check if your CMSMS system needs upgrading\">here</a> to check if your site requires a database upgrade. After you have confirmed you are up to date, then we can get cracking on the site development! </p>\r\n\r\n<p>These default pages are devoted to showing you the basics of how to get your site up with CMS Made Simple. </p>\r\n\r\n<p>To get to the Administration Panel you have to login as the administrator (with the username/password you mentioned during the installation process) on your site at http://yourwebsite.com/cmsmspath/admin. </p>\r\n\r\n<p>If you are right now on your own default install, you can probably just click <a title=\"CMSMS Demo Admin Panel\" href=\"admin/\">this link</a>. </p>\r\n\r\n<h3>Learning CMS Made Simple </h3>\r\n\r\n<p>On these example pages many of the features of the default installation of CMS Made Simple are described and demonstrated. You can learn about how to use different kinds of menus, templates, stylesheets and extensions. </p>\r\n\r\n<p>Read about how to use CMS Made Simple in the {cms_selflink ext=\"http://wiki.cmsmadesimple.org/\" title=\"CMS Made Simple Documentation\" text=\"documentation\" target=\"_blank\"}. In case you need any help the community is always at your service, in the \r\n{cms_selflink ext=\"http://forum.cmsmadesimple.org\" title=\"CMS Made Simple Forum\" text=\"forum\" target=\"_blank\"} or the {cms_selflink ext=\"http://www.cmsmadesimple.org/IRC.shtml\" title=\"Information about the CMS Made Simple IRC channel\" text=\"IRC\" target=\"_blank\"}. </p>\r\n\r\n<h3>License </h3>\r\n\r\n<p>CMS Made Simple is released under the {cms_selflink ext=\"http://www.gnu.org/licenses/licenses.html#GPL\" title=\"General Public License\" text=\"GPL\" target=\"_blank\"} license </p>','2007-11-20 17:39:47','2007-11-20 18:07:27'),(3,2,'cmscontentproperty','front_page_image-block-type','en_US','html','2007-11-20 17:39:47','2007-11-20 17:39:47'),(4,2,'cmscontentproperty','front_page_image-content','en_US','Test','2007-11-20 17:39:47','2007-11-20 17:39:47'),(5,2,'cmscontentproperty','name','en_US','Home','2007-11-20 17:39:47','2007-11-20 18:07:27'),(6,2,'cmscontentproperty','menu_text','en_US','Home','2007-11-20 17:39:47','2007-11-20 18:07:27'),(7,3,'cmscontentproperty','name','en_US','blog','2008-12-14 20:59:08','2008-12-14 20:59:08'),(8,3,'cmscontentproperty','menu_text','en_US','blog','2008-12-14 20:59:08','2008-12-14 20:59:08'),(9,3,'cmscontentproperty','default-block-type','en_US','html','2008-12-14 20:59:08','2008-12-14 20:59:08'),(10,3,'cmscontentproperty','default-content','en_US','{cms_module module=\'blog\'}\r\n','2008-12-14 20:59:08','2008-12-14 20:59:08');
/*!40000 ALTER TABLE `cms_content_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_crossref`
--

DROP TABLE IF EXISTS `cms_crossref`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_crossref` (
  `child_type` varchar(100) default NULL,
  `child_id` int(11) default NULL,
  `parent_type` varchar(100) default NULL,
  `parent_id` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  KEY `child_type_and_id` (`child_type`,`child_id`),
  KEY `parent_type_and_id` (`parent_type`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_crossref`
--

LOCK TABLES `cms_crossref` WRITE;
/*!40000 ALTER TABLE `cms_crossref` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_crossref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_event_handlers`
--

DROP TABLE IF EXISTS `cms_event_handlers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_event_handlers` (
  `id` int(11) NOT NULL auto_increment,
  `event_id` int(11) default NULL,
  `tag_name` varchar(255) default NULL,
  `module_name` varchar(100) default NULL,
  `removable` int(11) default NULL,
  `handler_order` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_event_handlers`
--

LOCK TABLES `cms_event_handlers` WRITE;
/*!40000 ALTER TABLE `cms_event_handlers` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_event_handlers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_events`
--

DROP TABLE IF EXISTS `cms_events`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_events` (
  `id` int(11) NOT NULL auto_increment,
  `originator` varchar(200) NOT NULL,
  `event_name` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `originator` (`originator`),
  KEY `event_name` (`event_name`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_events`
--

LOCK TABLES `cms_events` WRITE;
/*!40000 ALTER TABLE `cms_events` DISABLE KEYS */;
INSERT INTO `cms_events` VALUES (1,'Core','LoginPost'),(2,'Core','LogoutPost'),(3,'Core','LoginFailed'),(4,'Core','AddUserPre'),(5,'Core','AddUserPost'),(6,'Core','EditUserPre'),(7,'Core','EditUserPost'),(8,'Core','DeleteUserPre'),(9,'Core','DeleteUserPost'),(10,'Core','AddGroupPre'),(11,'Core','AddGroupPost'),(12,'Core','EditGroupPre'),(13,'Core','EditGroupPost'),(14,'Core','DeleteGroupPre'),(15,'Core','DeleteGroupPost'),(16,'Core','AddStylesheetPre'),(17,'Core','AddStylesheetPost'),(18,'Core','EditStylesheetPre'),(19,'Core','EditStylesheetPost'),(20,'Core','DeleteStylesheetPre'),(21,'Core','DeleteStylesheetPost'),(22,'Core','AddTemplatePre'),(23,'Core','AddTemplatePost'),(24,'Core','EditTemplatePre'),(25,'Core','EditTemplatePost'),(26,'Core','DeleteTemplatePre'),(27,'Core','DeleteTemplatePost'),(28,'Core','TemplatePreCompile'),(29,'Core','TemplatePostCompile'),(30,'Core','AddGlobalContentPre'),(31,'Core','AddGlobalContentPost'),(32,'Core','EditGlobalContentPre'),(33,'Core','EditGlobalContentPost'),(34,'Core','DeleteGlobalContentPre'),(35,'Core','DeleteGlobalContentPost'),(36,'Core','GlobalContentPreCompile'),(37,'Core','GlobalContentPostCompile'),(38,'Core','ContentEditPre'),(39,'Core','ContentEditPost'),(40,'Core','ContentDeletePre'),(41,'Core','ContentDeletePost'),(42,'Core','AddUserDefinedTagPre'),(43,'Core','AddUserDefinedTagPost'),(44,'Core','EditUserDefinedTagPre'),(45,'Core','EditUserDefinedTagPost'),(46,'Core','DeleteUserDefinedTagPre'),(47,'Core','DeleteUserDefinedTagPost'),(48,'Core','ModuleInstalled'),(49,'Core','ModuleUninstalled'),(50,'Core','ModuleUpgraded'),(51,'Core','AllModulesLoaded'),(52,'Core','HeaderTagRender'),(53,'Core','ContentStylesheet'),(54,'Core','ContentPreCompile'),(55,'Core','ContentPostCompile'),(56,'Core','ContentPostRender'),(57,'Core','SmartyPreCompile'),(58,'Core','SmartyPostCompile'),(59,'Core','SearchReindex'),(60,'Core','AdminDisplayStart'),(61,'Core','AdminDisplayFinish'),(62,'Core','MissingTranslation');
/*!40000 ALTER TABLE `cms_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_group_permissions`
--

DROP TABLE IF EXISTS `cms_group_permissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_group_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `permission_defn_id` int(11) default NULL,
  `group_id` int(11) default NULL,
  `object_id` int(11) default NULL,
  `has_access` tinyint(4) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_group_permissions`
--

LOCK TABLES `cms_group_permissions` WRITE;
/*!40000 ALTER TABLE `cms_group_permissions` DISABLE KEYS */;
INSERT INTO `cms_group_permissions` VALUES (2,1,1,1,1);
/*!40000 ALTER TABLE `cms_group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_group_perms`
--

DROP TABLE IF EXISTS `cms_group_perms`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_group_perms` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) default NULL,
  `permission_id` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `group_and_permission` (`group_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_group_perms`
--

LOCK TABLES `cms_group_perms` WRITE;
/*!40000 ALTER TABLE `cms_group_perms` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_group_perms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_groups`
--

DROP TABLE IF EXISTS `cms_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_groups` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` varchar(25) default NULL,
  `active` tinyint(4) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_groups`
--

LOCK TABLES `cms_groups` WRITE;
/*!40000 ALTER TABLE `cms_groups` DISABLE KEYS */;
INSERT INTO `cms_groups` VALUES (1,'Admin',1,'2007-11-25 16:01:31','2007-11-25 16:01:31'),(2,'Anonymous',1,'2007-11-25 16:01:31','2007-11-25 16:01:31');
/*!40000 ALTER TABLE `cms_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_htmlblobs`
--

DROP TABLE IF EXISTS `cms_htmlblobs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_htmlblobs` (
  `id` int(11) NOT NULL auto_increment,
  `htmlblob_name` varchar(255) default NULL,
  `html` longtext,
  `owner` int(11) default NULL,
  `version` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `htmlblob_name` (`htmlblob_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_htmlblobs`
--

LOCK TABLES `cms_htmlblobs` WRITE;
/*!40000 ALTER TABLE `cms_htmlblobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_htmlblobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_language_definitions`
--

DROP TABLE IF EXISTS `cms_language_definitions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_language_definitions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(75) default NULL,
  `english_name` varchar(75) default NULL,
  `aliases` varchar(255) default NULL,
  `code` varchar(10) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_language_definitions`
--

LOCK TABLES `cms_language_definitions` WRITE;
/*!40000 ALTER TABLE `cms_language_definitions` DISABLE KEYS */;
INSERT INTO `cms_language_definitions` VALUES (1,'Afrikaans','Africaans','af,afr,afrikaans,af_ZA.ISO8859-1','af_ZA',NULL,NULL),(2,'Български','Bulgarian','bg,bulgari','bg_BG',NULL,NULL),(3,'Català','Catalan','ca,cat,català,ca_ES.ISO8859-1','ca_ES',NULL,NULL),(4,'Česky','Czech','cs,czech,cze,cs_CS,cs_CZ.WINDOWS-1250,cs_CZ.ISO8859-2','cs_CZ',NULL,NULL),(5,'Dansk','Danish','dk,dansk,dan,da_DK,da_DK.ISO8859-1','da_DK',NULL,NULL),(6,'Deutsch','German','de,deutsch,deu,de_DE.ISO8859-1','de_DE',NULL,NULL),(7,'Ελληνικα','Greek','gr,greek,hellenic,el,el_GR.ISO8859-7','el_GR',NULL,NULL),(8,'English','English','en,english,eng,en_CA,en_GB,en_US.ISO8859-1','en_US',NULL,NULL),(9,'Español','Spanish','es,espanol,español,esp,es_AR,es_PE,es_MX','es_ES',NULL,NULL),(10,'Eesti','Estonian','et,estonian,eti,et_EE.ISO8859-1,et_EE.ISO8859-15,et_EE.UTF-8','et_EE',NULL,NULL),(11,'Euskara','Basque','eu,basque,baq,eus,eu_ES,eu_ES.ISO8859-1','eu_ES',NULL,NULL),(12,'Suomi','Finnish','fi,finnish,fi_FI.ISO8859-1,fi_FI.ISO8859-15','fi_FI',NULL,NULL),(13,'Français','French','fr,french,fra,fr_BE,fr_CA,fr_LU,fr_CH,fr_FR.ISO8859-1','fr_FR',NULL,NULL),(14,'Magyar','Hungarian','hu,hungarian,magyar,hu_HU.WINDOWS-1250,hu_HU.ISO8859-2','hu_HU',NULL,NULL),(15,'Bahasa Indonesia','Indonesian','id,ind,id_ID,id_ID.ISO8859-15','id_ID',NULL,NULL),(16,'Íslenska','Icelandic','is,icelandic,ice,isl,is_IS.ISO8859-1,is_IS.ISO8859-15','is_IS',NULL,NULL),(17,'Italiano','Italian','it,italiano,ita,italian,it_IT.ISO8859-1,it_IT.ISO8859-15','it_IT',NULL,NULL),(18,'日本語','Japanese','ja,japanese,jap,ja_JP.EUC-JP,ja_JP.Shift_JIS,ja_JP.UTF-8','ja_JP',NULL,NULL),(19,'Lietuvių','Lithuanian','lt,lithuanian,lt_LT,lt_LT.ISO8859-13','lt_LT',NULL,NULL),(20,'Norsk bokmål','Norwegian','no,norwegian,nor,nb_NO.ISO8859-1,nb_NO.ISO8859-15','nb_NO',NULL,NULL),(21,'Nederlands','Dutch','dutch,nl_NL.ISO8859-1','nl_NL',NULL,NULL),(22,'Polski','Polish','pl,polish,pl_PL.ISO8859-2','pl_PL',NULL,NULL),(23,'Português Brasileiro','Portuguese [Brazilian]','pt-BR','pt_BR',NULL,NULL),(24,'Português','Portuguese','pt-PT','pt_PT',NULL,NULL),(25,'Русский','Russian','ru,russian,rus','ru_RU',NULL,NULL),(26,'Slovenčina','Slovak','sk,slovak,svk,sk_SK,sk_SK.WINDOWS-1250,sk_SK.ISO8859-2','sk_SK',NULL,NULL),(27,'српски Srpski','Serbian','sr,serbian,srb,sr_YU,sr_YU.WINDOWS-1250,sr_YU.ISO8859-2,sr_YU.UTF-8','sr_YU',NULL,NULL),(28,'Svenska','Swedish','sv,svenska,sve,sv_SE,sv_SE.ISO8859-1,sv_SE.ISO8859-15','sv_SE',NULL,NULL),(29,'Türkçe','Turkish','tr,turkish,trk,tr_TR.ISO8859-9,tr_TR.UTF-8','tr_TR',NULL,NULL),(30,'简体中文','Simplified Chinese','zh_CN.EUC,chinese_gb2312','zh_CN',NULL,NULL),(31,'繁體中文','Traditional Chinese','chinese,zh_TW.Big5','zh_TW',NULL,NULL);
/*!40000 ALTER TABLE `cms_language_definitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_language_keys`
--

DROP TABLE IF EXISTS `cms_language_keys`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_language_keys` (
  `id` int(11) NOT NULL auto_increment,
  `module_name` varchar(100) NOT NULL,
  `key_string` text,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_language_keys`
--

LOCK TABLES `cms_language_keys` WRITE;
/*!40000 ALTER TABLE `cms_language_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_language_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_language_values`
--

DROP TABLE IF EXISTS `cms_language_values`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_language_values` (
  `id` int(11) NOT NULL auto_increment,
  `language_key_id` int(11) default NULL,
  `language_code` varchar(10) default NULL,
  `value` longtext,
  `modified` tinyint(4) default '0',
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_language_values`
--

LOCK TABLES `cms_language_values` WRITE;
/*!40000 ALTER TABLE `cms_language_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_language_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_module_deps`
--

DROP TABLE IF EXISTS `cms_module_deps`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_module_deps` (
  `parent_module` varchar(100) default NULL,
  `child_module` varchar(100) default NULL,
  `minimum_version` varchar(25) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_module_deps`
--

LOCK TABLES `cms_module_deps` WRITE;
/*!40000 ALTER TABLE `cms_module_deps` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_module_deps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_module_templates`
--

DROP TABLE IF EXISTS `cms_module_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_module_templates` (
  `id` int(11) NOT NULL auto_increment,
  `module_name` varchar(100) NOT NULL,
  `template_type` varchar(100) NOT NULL default '',
  `template_name` varchar(150) NOT NULL,
  `content` longtext,
  `default_template` tinyint(4) default '0',
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `module_and_template` (`module_name`,`template_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_module_templates`
--

LOCK TABLES `cms_module_templates` WRITE;
/*!40000 ALTER TABLE `cms_module_templates` DISABLE KEYS */;
INSERT INTO `cms_module_templates` VALUES (1,'Blog','summary','Default Template','{foreach from=$posts item=entry}\n<h3><a href=\"{$entry->url}\">{$entry->title}</a></h3>\n<small>\n  {$entry->post_date} \n  {if $entry->author ne null}\n    {mod_lang string=by} {$entry->author->full_name()}\n  {/if}\n</small>\n\n<div>\n{$entry->get_summary_for_frontend()}\n</div>\n\n{if $entry->has_more() eq true}\n  <a href=\"{$entry->url}\">{mod_lang string=hasmore} &gt;&gt;</a>\n{/if}\n\n{/foreach}',1,'2008-12-10 13:18:23','2008-12-10 13:18:23'),(2,'Blog','detail','Default Template','{if $post ne null}\n<h3>{$post->title}</h3>\n<small>\n  {$post->post_date} \n  {if $post->author ne null}\n    {mod_lang string=by} {$post->author->full_name()}\n  {/if}\n</small>\n\n<div>\n{$post->content}\n</div>\n\n<hr />\n\n<p>\n{cms_module module=\"comments\" module_name=\"blog\" content_id=$post->id}\n</p>\n\n{else}\n{mod_lang string=postnotfound}\n{/if}',1,'2008-12-10 13:18:23','2008-12-10 13:18:23'),(3,'Blog','rss','Default Template','<?xml version=\"1.0\"?>\n<rss version=\"2.0\">\n	<channel>\n		<title>{$sitename|escape}</title>\n		<link>{root_url}</link>\n		{foreach from=$posts item=entry}\n		<item>\n			<title><![CDATA[{$entry->title}]]></title>\n			<link>{$entry->url}</link>\n			<guid>{$entry->url}</guid>\n			<pubDate>{$entry->post_date}</pubDate>\n			<category><![CDATA[]]></category>\n			<description><![CDATA[{$entry->get_summary_for_frontend()}]]></description>\n		</item>\n		{/foreach}\n	</channel>\n</rss> \n',1,'2008-12-10 13:18:23','2008-12-10 13:18:23'),(4,'UserAdmin','login','Default Login Form','{* UserAdmin login form *}\n{if !empty($error)}\n<p><strong>{$error}</strong></p>\n{/if}\n{mod_form action=\'login\' inline=\'true\'}\n<p>{tr}Username{/tr}:&nbsp;{mod_textbox name=\'username\' size=\'25\' maxlength=\'25\'}</p>\n<p>{tr}Password{/tr}:&nbsp;{mod_password name=\'password\' size=\'25\' maxlength=\'25\'}</p>\n<p>{tr}OpenID{/tr}:&nbsp;{mod_text name=\'openid\' size=\'15\' maxlength=\'15\'}</p>\n<p>{mod_submit name=\'submit\' value=\'submit\'}</p>\n{/mod_form}',1,'2008-12-14 18:27:51','2008-12-14 18:27:51'),(5,'MenuManager','menu_template','Default Template','{if $count > 0}\n	<ul>\n		{foreach from=$nodelist item=node}\n			{if $node->show}\n				<li>\n					<a href=\"{$node->url}\">{$node->menutext}</a>\n					{menu_children node=$node}\n				</li>\n			{/if}\n		{/foreach}\n	</ul>\n{/if}\n',1,'2008-12-14 18:27:57','2008-12-14 18:27:57');
/*!40000 ALTER TABLE `cms_module_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_modules`
--

DROP TABLE IF EXISTS `cms_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_modules` (
  `module_name` varchar(100) default NULL,
  `status` varchar(50) default NULL,
  `version` varchar(50) default NULL,
  `admin_only` tinyint(4) default '0',
  `active` tinyint(4) default NULL,
  KEY `module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_modules`
--

LOCK TABLES `cms_modules` WRITE;
/*!40000 ALTER TABLE `cms_modules` DISABLE KEYS */;
INSERT INTO `cms_modules` VALUES ('Blog','installed','0.1',0,1),('UserAdmin','installed','0.1',0,1),('MenuManager','installed','2.0',0,1);
/*!40000 ALTER TABLE `cms_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_multilanguage`
--

DROP TABLE IF EXISTS `cms_multilanguage`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_multilanguage` (
  `id` int(11) NOT NULL auto_increment,
  `module_name` varchar(100) default NULL,
  `content_type` varchar(25) default NULL,
  `object_id` int(11) default NULL,
  `property_name` varchar(100) default NULL,
  `language` varchar(5) default NULL,
  `content` longtext,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_multilanguage`
--

LOCK TABLES `cms_multilanguage` WRITE;
/*!40000 ALTER TABLE `cms_multilanguage` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_multilanguage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_permission_defns`
--

DROP TABLE IF EXISTS `cms_permission_defns`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_permission_defns` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(100) default NULL,
  `extra_attr` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `hierarchical` tinyint(4) default '0',
  `link_table` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_permission_defns`
--

LOCK TABLES `cms_permission_defns` WRITE;
/*!40000 ALTER TABLE `cms_permission_defns` DISABLE KEYS */;
INSERT INTO `cms_permission_defns` VALUES (1,'Core','Page','View',1,'content'),(2,'Core','Page','Edit',1,'content'),(3,'Core','Page','Delete',1,'content'),(4,'Core','','Manage Groups',0,NULL),(5,'Core','','Manage Users',0,NULL),(6,'Core','','Manage Layout',0,NULL),(7,'Core','','Manage Modules',0,NULL),(8,'Core','','Manage Files',0,NULL),(9,'Core','','Manage Site Preferences',0,NULL),(10,'Core','','Manage User-defined Tags',0,NULL),(11,'Core','','Manage Global Content Blocks',0,NULL),(12,'Core','','Manage Events',0,NULL),(13,'Core','','Admin Login',0,NULL);
/*!40000 ALTER TABLE `cms_permission_defns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_permissions`
--

DROP TABLE IF EXISTS `cms_permissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `permission_name` varchar(255) default NULL,
  `permission_text` varchar(255) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_permissions`
--

LOCK TABLES `cms_permissions` WRITE;
/*!40000 ALTER TABLE `cms_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_serialized_versions`
--

DROP TABLE IF EXISTS `cms_serialized_versions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_serialized_versions` (
  `id` int(11) NOT NULL auto_increment,
  `version` int(11) default NULL,
  `object_id` int(11) default NULL,
  `data` longblob,
  `type` varchar(255) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_serialized_versions`
--

LOCK TABLES `cms_serialized_versions` WRITE;
/*!40000 ALTER TABLE `cms_serialized_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_serialized_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_siteprefs`
--

DROP TABLE IF EXISTS `cms_siteprefs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_siteprefs` (
  `sitepref_name` varchar(255) NOT NULL,
  `sitepref_value` text,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`sitepref_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_siteprefs`
--

LOCK TABLES `cms_siteprefs` WRITE;
/*!40000 ALTER TABLE `cms_siteprefs` DISABLE KEYS */;
INSERT INTO `cms_siteprefs` VALUES ('custom404','<p>Page could not be found.</p>','2006-07-25 21:22:33','2006-07-25 21:22:33'),('custom404template','-1','2006-07-25 21:22:33','2006-07-25 21:22:33'),('enablecustom404','0','2006-07-25 21:22:33','2006-07-25 21:22:33'),('enablesitedownmessage','0','2006-07-25 21:22:33','2006-07-25 21:22:33'),('logintheme','default','2006-07-25 21:22:33','2006-07-25 21:22:33'),('metadata','<meta name=\"Generator\" content=\"CMS Made Simple - Copyright (C) 2004-6 Ted Kulp. All rights reserved.\" />\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n ','2006-07-25 21:22:33','2006-07-25 21:22:33'),('sitedownmessage','<p>Site is currently down for maintenance.</p>','2006-07-25 21:22:33','2006-07-25 21:22:33'),('sitedownmessagetemplate','-1','2006-07-25 21:22:33','2006-07-25 21:22:33'),('useadvancedcss','1','2006-07-25 21:22:33','2006-07-25 21:22:33'),('xmlmodulerepository',NULL,'2006-07-25 21:22:33','2006-07-25 21:22:33');
/*!40000 ALTER TABLE `cms_siteprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_stylesheet_template_assoc`
--

DROP TABLE IF EXISTS `cms_stylesheet_template_assoc`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_stylesheet_template_assoc` (
  `id` int(11) NOT NULL auto_increment,
  `stylesheet_id` int(11) default NULL,
  `template_id` int(11) default NULL,
  `order_num` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `stylesheet_id` (`stylesheet_id`),
  KEY `template_id` (`template_id`),
  KEY `stylesheet_id_template_id` (`stylesheet_id`,`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_stylesheet_template_assoc`
--

LOCK TABLES `cms_stylesheet_template_assoc` WRITE;
/*!40000 ALTER TABLE `cms_stylesheet_template_assoc` DISABLE KEYS */;
INSERT INTO `cms_stylesheet_template_assoc` VALUES (1,2,1,1,NULL,NULL);
/*!40000 ALTER TABLE `cms_stylesheet_template_assoc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_stylesheets`
--

DROP TABLE IF EXISTS `cms_stylesheets`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_stylesheets` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `value` longtext,
  `active` tinyint(4) default '1',
  `media_type` varchar(255) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_stylesheets`
--

LOCK TABLES `cms_stylesheets` WRITE;
/*!40000 ALTER TABLE `cms_stylesheets` DISABLE KEYS */;
INSERT INTO `cms_stylesheets` VALUES (2,'Test','Test',1,'all','2009-01-19 23:34:06','2009-01-19 23:34:06');
/*!40000 ALTER TABLE `cms_stylesheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_tag_objects`
--

DROP TABLE IF EXISTS `cms_tag_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_tag_objects` (
  `tag_id` int(11) default NULL,
  `name` varchar(255) default NULL,
  `type` varchar(25) default NULL,
  `object_id` int(11) default NULL,
  KEY `tag_object_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_tag_objects`
--

LOCK TABLES `cms_tag_objects` WRITE;
/*!40000 ALTER TABLE `cms_tag_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_tag_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_tags`
--

DROP TABLE IF EXISTS `cms_tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `tag_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_tags`
--

LOCK TABLES `cms_tags` WRITE;
/*!40000 ALTER TABLE `cms_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_templates`
--

DROP TABLE IF EXISTS `cms_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_templates` (
  `id` int(11) NOT NULL auto_increment,
  `template_name` varchar(255) default NULL,
  `template_content` longtext,
  `encoding` varchar(25) default NULL,
  `active` tinyint(4) default '1',
  `default_template` tinyint(4) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `template_name` (`template_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_templates`
--

LOCK TABLES `cms_templates` WRITE;
/*!40000 ALTER TABLE `cms_templates` DISABLE KEYS */;
INSERT INTO `cms_templates` VALUES (1,'Minimal Template','{* Change lang=\"en\" to the language of your site *}\r\n\r\n\r\n\r\n{sitename} - {title}\r\n{* The sitename is changed in Site Admin/Global settings. {title} is the name of each page *}\r\n\r\n{header}\r\n{* This is how all the stylesheets attached to this template are linked to and where metadata is displayed *}\r\n\r\n\r\n\r\n\r\n\r\n      {* Start Navigation *}\r\n      \r\n         {language_selector}\r\n         {menu}\r\n      \r\n      {* End Navigation *}\r\n\r\n      {* Start Content *}\r\n      \r\n         {title}\r\n         {content}\r\n      \r\n      {* End Content *}\r\n\r\n\r\n','',1,1,'2009-01-17 22:31:05','2009-01-17 22:39:19');
/*!40000 ALTER TABLE `cms_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_user_groups`
--

DROP TABLE IF EXISTS `cms_user_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_user_groups` (
  `group_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_user_groups`
--

LOCK TABLES `cms_user_groups` WRITE;
/*!40000 ALTER TABLE `cms_user_groups` DISABLE KEYS */;
INSERT INTO `cms_user_groups` VALUES (1,1,'2008-12-09 06:16:17','2008-12-09 06:16:17');
/*!40000 ALTER TABLE `cms_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_userplugins`
--

DROP TABLE IF EXISTS `cms_userplugins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_userplugins` (
  `id` int(11) NOT NULL auto_increment,
  `userplugin_name` varchar(255) default NULL,
  `code` text,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_userplugins`
--

LOCK TABLES `cms_userplugins` WRITE;
/*!40000 ALTER TABLE `cms_userplugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_userplugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_userprefs`
--

DROP TABLE IF EXISTS `cms_userprefs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_userprefs` (
  `user_id` int(11) default NULL,
  `preference` varchar(50) default NULL,
  `value` text,
  `type` varchar(25) default NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_userprefs`
--

LOCK TABLES `cms_userprefs` WRITE;
/*!40000 ALTER TABLE `cms_userprefs` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_userprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_users`
--

DROP TABLE IF EXISTS `cms_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(25) default NULL,
  `password` varchar(40) default NULL,
  `first_name` varchar(50) default NULL,
  `last_name` varchar(50) default NULL,
  `email` varchar(255) default NULL,
  `openid` varchar(255) default NULL,
  `checksum` varchar(255) default NULL,
  `active` tinyint(4) default NULL,
  `create_date` datetime default NULL,
  `modified_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_users`
--

LOCK TABLES `cms_users` WRITE;
/*!40000 ALTER TABLE `cms_users` DISABLE KEYS */;
INSERT INTO `cms_users` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','Ted','Kulp','blah@blah.com','http://tedkulp.com/','',1,'2008-12-09 06:16:17','2008-12-09 06:16:17');
/*!40000 ALTER TABLE `cms_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_version`
--

DROP TABLE IF EXISTS `cms_version`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cms_version` (
  `version` int(11) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cms_version`
--

LOCK TABLES `cms_version` WRITE;
/*!40000 ALTER TABLE `cms_version` DISABLE KEYS */;
INSERT INTO `cms_version` VALUES (1);
/*!40000 ALTER TABLE `cms_version` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-02-02  0:53:37
