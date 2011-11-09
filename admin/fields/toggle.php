<?php
/**
 * @package     Expose
 * @version     2.0    Mar 14, 2011
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
        $checked	= ((string) $this->element['value'] == $this->value) ? ' checked="checked"' : '';
        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'.(string)$this->element['pretext'].'</span>' : '';
        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.(string)$this->element['posttext'].'</span>' : '';

        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        if(!defined('EXPOSE_TOGGLE')){
            define('EXPOSE_TOGGLE', 1);
            $expose->addScript($expose->exposeUrl.'admin/widgets/toggle/js/toggle.js');
        }

        $input = '<input class="toggle '.$class.'" type="checkbox" name="'.$this->name.'" id="'.$this->id.'"' .
                        ' value="'.htmlspecialchars((string) $this->element['value'], ENT_COMPAT, 'UTF-8').'"' .
                        $checked.$disabled.'/>';

        return $wrapstart . $pretext . $input .$wrapend;
    }
}

