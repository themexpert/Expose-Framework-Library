<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        toggle.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldToggle extends JFormField{

    protected $type = 'Toggle';

    public function getInput(){
        global $expose;
        $output = NULL;

        // Initialize some field attributes.
        $class		= $this->element['class'];
        $disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $checked	= ($this->value == 1) ? ' checked="checked"' : '';

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        if(!defined('EXPOSE_TOGGLE')){
            define('EXPOSE_TOGGLE', 1);
            $expose->addLink($expose->exposeUrl.'/admin/widgets/toggle/js/toggle.js','js');
        }

        $input = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'" />'."\n".'
                    <input class="toggle" type="checkbox" id="'.$this->id.'"' .' value="'.$this->value.'"' . $checked.$disabled.'/>';

        return $wrapstart . $pretext . $input .$wrapend;
    }
}

