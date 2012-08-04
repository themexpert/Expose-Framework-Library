<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        core.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldModulePositions extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'ModulePositions';

	protected function getInput() {
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

        if(!defined('EXPOSE_MENU_PARAMS')){
            define('EXPOSE_MENU_PARAMS',1);
            $uri = str_replace(DS,"/",str_replace( JPATH_SITE, JURI::base (), dirname(__FILE__) ));
            $uri = str_replace("/administrator/", "", $uri);
            $jquery_uri = str_replace('admin/fields','interface/js',$uri) ;
            $doc = JFactory::getDocument();

            $doc->addScript($jquery_uri.'/jquery-1.7.1.min.js');

            $js = "
                jQuery.noConflict();
                jQuery(document).ready(function(){
                    var modContainer = jQuery('#jformparamsmega_subcontent_mod_modules').parent();
                    var posContainer = jQuery('#jformparamsmega_subcontent_pos_positions').parent();

                    function showMod(){
                        modContainer.show();
                        posContainer.hide();
                    }
                    function showPos(){
                        modContainer.hide();
                        posContainer.show();
                    }
                    function hideAll(){
                        modContainer.hide();
                        posContainer.hide();
                    }

                    //check default checked status
                    var selec = 'jform[params][mega_subcontent]';
                    var val = jQuery(\"#jform_params_mega_subcontent input[type='radio']:checked\").val();

                    switch(val){
                        case 'mod':
                            showMod();
                            break;
                        case 'pos':
                            showPos();
                            break;
                        default:
                            hideAll();
                    }

                    //click event
                    jQuery('#jform_params_mega_subcontent0').click(function(){
                        hideAll();
                    });
                    jQuery('#jform_params_mega_subcontent1').click(function(){
                        showMod();
                    });
                    jQuery('#jform_params_mega_subcontent2').click(function(){
                        showPos();
                    });

                });
            ";
            $doc->addScriptDeclaration($js);

        }
		
		return $lists;
	}
} 