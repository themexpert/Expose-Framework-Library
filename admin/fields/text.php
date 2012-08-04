<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 19, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        text.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldText extends JFormField
{
	protected $type = 'Text';

        protected function getInput()
	{
            $output = NULL;
            // Initialize some field attributes.
            $size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
            $maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
            $class      = $this->element['class'];
            $readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
            $disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
            
            $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

            $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
            $wrapend    = '</div>';

            $input = '<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
                            ' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
                            $size.$disabled.$readonly.$maxLength.'/>';

            $output = $wrapstart . $pretext . $input . $posttext . $wrapend;
            return $output;
	}

}



