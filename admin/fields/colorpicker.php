<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        colorpicker.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldColorPicker extends JFormField{

    protected  $type = 'ColorPicker';

    protected function getInput(){
        global $expose;
        $output = NULL;
        $input = '';
        
        if(!defined('EXPOSE_COLOR_PICKER')){
            define('EXPOSE_COLOR_PICKER', 1);
            $expose->addLink($expose->exposeUrl.'/admin/widgets/colorpicker/js/colorpicker.js','js');
        }

        $class		= (string) $this->element['class'];
        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        $js = "
            $('#$this->id').ColorPicker({
                color: '{$this->value}',
                onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    $('#$this->id div').css('backgroundColor', '#' + hex);
                    $('#$this->id-field').val(hex);
                    $('#$this->id-field').parent().addClass('highlight');
                },
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val(hex);
                    $(el).ColorPickerHide();
                }

            });";
        $expose->addjQDom($js);

        $input .= '<input class="picker" type="text" name="'.$this->name.'" id="'.$this->id.'-field"' .
                   ' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' . '/>';
        $input .= '<div id="'.$this->id.'" class="color-selector"><div style="background-color:#'.$this->value.'"></div></div>';

        return $wrapstart . $pretext . $input . $wrapend;
    }
}

