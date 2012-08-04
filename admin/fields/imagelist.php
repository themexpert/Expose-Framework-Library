<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

// restricted access
defined('_JEXEC') or die( 'Restricted access' );
//import necessary classes
jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldImageList extends JFormField
{

	public $type = 'ImageList';

	 protected function getInput(){

        global $expose;
        $html = array();
        $options = array();
        //get template id
        $id = JRequest::getInt('id');

        // Initialize some field attributes.
        $filter			= '\.png$|\.gif$|\.jpg$|\.bmp$|\.jpeg$';
		$exclude		= (string) $this->element['exclude'];
		$stripExt		= (string) $this->element['stripext'];
		$hideNone		= (string) $this->element['hide_none'];
		//$hideDefault	= (string) $this->element['hide_default'];
        $class = $this->element['class'];
        $attr = $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="field-wrap patterns clearfix '.$class.'">';
        $wrapend    = '</div>';

        // Get the path in which to search for file options.
		$directory = (string) $this->element['directory'];

        $path = JPATH_ROOT . '/templates/' . getTemplate($id) . '/images/' .$directory;


         // Prepend some default options based on field attributes.
		if (!$hideNone) {
			$options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}
//		if (!$hideDefault) {
//			$options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
//		}
         
        // Get a list of files in the search path with the given filter.
		$files = JFolder::files($path, $filter);

        // Build the options list from the list of files.
		if (is_array($files)) {
			foreach($files as $file) {

				// Check to see if the file is in the exclude mask.
				if ($exclude) {
					if (preg_match(chr(1).$exclude.chr(1), $file)) {
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($stripExt) {
					$file = JFile::stripExt($file);
				}

				$options[] = JHtml::_('select.option', $file, $file);
			}
		}

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
}