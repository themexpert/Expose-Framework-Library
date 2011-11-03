<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        expose.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldPositions extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Positions';

	protected function getInput() {
        if(!defined('EXPOSE_MENU_PARAMS')){
            define('EXPOSE_MENU_PARAMS',1);
            $uri = str_replace(DS,"/",str_replace( JPATH_SITE, JURI::base (), dirname(__FILE__) ));
			$uri = str_replace("/administrator/", "", $uri);
            $jquery_uri = str_replace('admin/fields','interface/js',$uri) ;
            $uri = str_replace("fields",'widgets',$uri);

            JHtml::script($jquery_uri.'/jquery-1.5.1.min.js');
            JHtml::script($uri.'/menu.params.js');
        }
        
		$db =& JFactory::getDBO();
		$query = "SELECT DISTINCT position FROM #__modules ORDER BY position ASC";
		$db->setQuery($query);
		$groups = $db->loadObjectList();
		
		$groupHTML = array();	
		if ($groups && count ($groups)) {
			foreach ($groups as $v=>$t){
				$groupHTML[] = JHTML::_('select.option', $t->position, $t->position);
			}
		}
		$lists = JHTML::_('select.genericlist', $groupHTML, $this->name.'[]', ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);
		
		return $lists;
	}
} 