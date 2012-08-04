<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2012 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
 
// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldLayouts extends JFormField{

    protected $type = 'Layouts';

    protected function getInput(){

        global $expose;

        $html = '';

        //dirty logic, need to do more interactive later
        ($this->value == 'content-sidebara-sidebarb') ? $colLeft = ' active': $colLeft = '';
        ($this->value == 'sidebara-content-sidebarb') ? $colMiddle = ' active': $colMiddle = '';
        ($this->value == 'sidebara-sidebarb-content') ? $colRight = ' active': $colRight = '';

        $html .= "<div id='layout-selector'>";
            $html .= "<span class='content-sidebara-sidebarb".$colLeft."'>Three Column Left</span>";
            $html .= "<span class='sidebara-content-sidebarb".$colMiddle."'>Three Column Middle</span>";
            $html .= "<span class='sidebara-sidebarb-content".$colRight."'>Three Column Right</span>";
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";

        return $html;

    }


}