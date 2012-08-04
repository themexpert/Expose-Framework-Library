<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 17, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        slider.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldSlider extends JFormField{

    protected $type = 'Slider';

    protected function getInput(){
        global $expose;
        $output = NULL;

        // Initialize some field attributes.
        $class		= (string) $this->element['class'];
        $disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        //$checked	= ((string) $this->element['value'] == $this->value) ? ' checked="checked"' : '';
        $min            = $this->element['min'] ? (int)$this->element['min'] : 0;
        $max            = $this->element['max'] ? (int)$this->element['max'] : 100;

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="slider-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        if(!defined('EXPOSE_SLIDER')){
            define('EXPOSE_SLIDER', 1);
            $js = '$(".slider-input").rangeinput({ progress: true });';
            $expose->addjQDom($js);
        }

        $input = '<input class="slider-input" type="text" min="'.$min.'" max="'.$max.'" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
				$disabled.'/>';

        return $output = $wrapstart . $pretext . $input . $posttext . $wrapend;
    }

}

