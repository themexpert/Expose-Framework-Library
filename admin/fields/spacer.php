<?php
/**
 * @package     Expose
 * @version     2.0    Mar 18, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        spacer.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldSpacer extends JFormField{

    protected $type = 'Spacer';

    protected function getInput(){
        return '';
    }

    protected function getLabel(){
        $html   = array();
        $class  = (string) $this->element['class'];
        $label  = '';

        // Get the label text from the XML element, defaulting to the element name.
        $text = $this->element['text'] ? (string) $this->element['text'] : '';
        $text = $this->translateLabel ? JText::_($text) : $text;


        // Add the label text and closing tag.
        if($text != NULL){
            $label .= '<div class="expose-spacer'.(($text != '') ? ' hasText hasTip' : '').'" title="::'. JText::_($this->description) .'"><span>' . JText::_($text) . '</span></div>';
        }

        $html[] = $label;

        return implode('', $html);
    }
}

