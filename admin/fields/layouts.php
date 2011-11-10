<?php
/**
 * @package     Expose
 * @version     3.0.0
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
        ($this->value == 'content.left.right') ? $colLeft = 'active': $colLeft = '';
        ($this->value == 'left.content.right') ? $colMiddle = 'active': $colMiddle = '';
        ($this->value == 'left.right.content') ? $colRight = 'active': $colRight = '';

        $html .= "<div id='layout-selector'>";
            $html .= "<span class='three-col-left ".$colLeft."' rel='content.left.right'>Three Column Left</span>";
            $html .= "<span class='three-col-middle ".$colMiddle."' rel='left.content.right'>Three Column Middle</span>";
            $html .= "<span class='three-col-right ".$colRight."' rel='left.right.content'>Three Column Right</span>";
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";

        $js = "

            jQuery('#layout-selector span').click(function(){
                var el = $(this);
                jQuery('#layout-selector span.active').removeClass('active');
                el.addClass('active');
                
                var rel = el.attr('rel');
                $('#jform_params_layout_type').attr('value',rel);

            });
        ";
        $expose->addjQDom($js);

        return $html;

    }


}