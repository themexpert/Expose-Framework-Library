<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 19, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        textarea.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldTextarea extends JFormField
{
	protected $type = 'Textarea';

	protected function getInput()
	{
            $output = NULL;
            // Initialize some field attributes.
            $class          = $this->element['class'];
            $disabled       = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
            $columns        = $this->element['cols'] ? ' cols="'.(int) $this->element['cols'].'"' : '';
            $rows           = $this->element['rows'] ? ' rows="'.(int) $this->element['rows'].'"' : '';

            $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

            $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
            $wrapend    = '</div>';


            $input  = '<textarea name="'.$this->name.'" id="'.$this->id.'"' .
                            $columns.$rows.$disabled.'>' .
                            JTEXT::_($this->value) .
                            '</textarea>';
            $output = $wrapstart . $pretext . $input . $posttext . $wrapend;
            return $output;

	}
}
