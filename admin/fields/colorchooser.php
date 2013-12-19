<?php
/**
 * @package     Expose
 * @version     4.0
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

class JFormFieldColorChooser extends JFormField{

    protected  $type = 'ColorChooser';

    protected function getInput()
    {
        // Check and define JVersion
        if ( version_compare(JVERSION, '2.5', 'ge') && version_compare(JVERSION, '3.0', 'lt') )
        {
            define('EXPOSE_JVERSION', '25');
        }else{
            define('EXPOSE_JVERSION', '30');
        }

        $input = '';
        $doc = JFactory::getDocument();

        // Load jQuery
        if( EXPOSE_JVERSION == '25')
        {
            $path = JURI::root(true) .'/libraries/expose/interface/js/jquery-1.8.3.min.js'; 
            //$path = 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js';
            $doc->addScript( $path );
        }else{
            JHtml::_('jquery.framework');
        }

        if(!defined('EXPOSE_COLOR_CHOOSER'))
        {
            define('EXPOSE_COLOR_CHOOSER', 1);
            $doc->addStyleSheet( JURI::root(true) .'/libraries/expose/admin/widgets/spectrum/spectrum.css' );
            $doc->addStyleSheet( JURI::root(true) .'/libraries/expose/admin/widgets/spectrum/style.css' );

            $doc->addScript( JURI::root(true) .'/libraries/expose/admin/widgets/spectrum/spectrum.js' );

            $js = "
                jQuery(document).ready(function($){
                    jQuery('.picker').spectrum({
                        showInput: true,
                        allowEmpty: true,
                        showAlpha: true,
                        preferredFormat: 'rgb',
                    });
                });
                ";
            $doc->addScriptDeclaration($js);

        }

        // $class		= (string) $this->element['class'];
        // $pretext    = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        // $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        // $wrapend    = '</div>';

        
        $input .= '<input class="picker" type="text" name="'.$this->name.'" id="'.$this->id .'" value="'. $this->value .'"' . '/>';

        return $input;

    }
}

