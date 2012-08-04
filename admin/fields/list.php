<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 19, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        list.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldList extends JFormField{

    protected $type = 'List';

    protected function getInput(){
        // Initialize variables.
        $html = array();
        $attr = '';

        // Initialize some field attributes.
        $class = $this->element['class'];

        // To avoid user's confusion, readonly="true" should imply disabled="true".
//        if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
//                $attr .= ' disabled="disabled"';
//        }

        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';
        // Get the field options.
        $options = (array) $this->getOptions();

        // Create a read-only list (no name) with a hidden input to store the value.
        if ((string) $this->element['readonly'] == 'true') {
                $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
                $html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
        }
        // Create a regular list.
        else {
                $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
        }

        return $wrapstart . $pretext. implode($html) . $posttext . $wrapend;
    }

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
    protected function getOptions()
    {
        // Initialize variables.
        $options = array();

        foreach ($this->element->children() as $option) {

                // Only add <option /> elements.
                if ($option->getName() != 'option') {
                        continue;
                }

                // Create a new option object based on the <option /> element.
                $tmp = JHtml::_('select.option', (string) $option['value'], JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', ((string) $option['disabled']=='true'));

                // Set some option attributes.
                $tmp->class = (string) $option['class'];

                // Set some JavaScript option attributes.
                $tmp->onclick = (string) $option['onclick'];

                // Add the option object to the result set.
                $options[] = $tmp;
        }

        reset($options);

        return $options;
    }
}


