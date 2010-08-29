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

/**
 * Interface for the definition for a module that provides
 * a Syntax Highlighter service for the system to use.  It 
 * might not be necessary to implement all the methods 
 * depending on how the highlighter is designed, but they 
 * must at least all be defined in your class.  If a 
 * method isn't needed, it should return null (or an empty 
 * string).
 *
 * Also, in order for the system to recognize this as
 * a Syntax Highlighter, the module must set the 
 * 'syntax_highlighter' capability in it's module.info file.
 *
 * @package cmsms
 */
interface CmsModuleSyntaxHighlighter
{
	/**
	 * Returns content destined for the <form> tag.	 It's useful if javascript is
	 * needed for the onsubmit of the form.
	 *
	 * @return string
	 */
	function syntax_page_form();
	
	/**
	 * This is a function that would be called before a form is submitted.
	 * Generally, a dropdown box or something similar that would force a submit
	 * of the form via javascript should put this in their onchange line as well
	 * so that the Syntax Highlighter can do any cleanups before the actual 
	 * form submission takes place.
	 *
	 * @return string
	 */
	function syntax_page_form_submit();

	/**
	 * Returns header code specific to this Syntax Highlighter
	 *
	 * @param string The html-code of the page before replacing Syntax Highlighter stuff
	 * @return string
	 */
	function syntax_generate_header($html_result = '');
	
	/**
	 * Returns body code specific to this Syntax Highlighter
	 *
	 * @return string
	 */
	function syntax_generate_body();
	
	/**
	 * Returns the textarea specific for this Syntax Highlighter.
	 *
	 * @param string HTML name of the textarea
	 * @param int Number of columns wide that the textarea should be
	 * @param int Number of rows long that the textarea should be
	 * @param string Encoding of the content
	 * @param string Content to show in the textarea
	 * @param string Stylesheet for content, if available
	 * @return string
	 */
	function syntax_textarea($name = 'textarea', $syntax = 'html', $columns = '80', $rows = '15', $encoding = '', $content = '', $stylesheet = '', $addtext = '');

}

# vim:ts=4 sw=4 noet
?>