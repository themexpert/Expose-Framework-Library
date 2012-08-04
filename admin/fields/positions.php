<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );


class JFormFieldPositions extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Positions';

	protected function getInput() {
        global $expose;
        $options = array();
        $html = array();
        $class		= $this->element['class'];
        //get template id
        $id = JRequest::getInt('id');
        
        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';
        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        $path = JPATH_ROOT . '/templates/' . getTemplate($id) .'/templateDetails.xml';

        if (file_exists($path)){
            $xml = simplexml_load_file($path);
            if (isset($xml->positions[0])){
                foreach ($xml->positions[0] as $position)
                {
                    $value = (string)$position['value'];
                    $label = (string)$position;
                    if (!$value) {
                        $value = $label;

                    }
                    $options[] = JHtml::_('select.option', $value, $value);
                }
            }
        }
        $html[] = JHtml::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value, $this->id);

        return $wrapstart . $pretext. implode($html) . $posttext . $wrapend;
    }
}