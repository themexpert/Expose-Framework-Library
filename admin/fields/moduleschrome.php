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

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldModulesChrome extends JFormField{

    protected $type = 'ModulesChrome';

    protected function getInput(){
        global $expose;

        $maxmods = (int) $this->element['max-mods'];
        $html = '';
        $options = array();
        $val = array();

        $id = str_replace('jform_params_','',$this->id);
        $name = str_replace('_chrome','',$id);
        $class		= $this->element['class'];

        $wrapstart  = '<div class="field-wrap clearfix '.$class.'">';
        $wrapend    = '</div>';

        //if the value field is empty or less then max, assign standard chrome to all position
        //$val = explode(',',$this->value);

        if(empty($this->value)){
            for($i=1; $i<=$maxmods; $i++)
            {
                $moduleName = $name . '-'. $i;
                $val[$moduleName] = 'standard';
            }
            //$val = array_shift($val);
        }else{
            $tempval = explode(',',$this->value);

            if(is_array($tempval)){
                foreach($tempval as $json)
                {
                    if(preg_match('/:/',$json)){
                        list($modName,$chrome) = explode(':',$json);
                        $val[$modName] = $chrome;
                    }else{
                        if(count($tempval) == 1){
                            $val[$name] = $json;
                        }else{
                            for($i=1; $i<=count($tempval); $i++){
                                $moduleName = $name . '-'. $i;
                                $val[$moduleName] = $json;
                            }
                        }
                    }
                }
            }else{
                $val[$name] = $this->value;
            }


        }

        $options = (array) $this->getOptions();

        //return print_r($val);
        //button
        $html .= "<p class='mod-chrome-button gradient hasTip' rel='#".$id."' title='::".JText::_($this->description)."'><span>".JText::_('MODULE_CHROME_LABEL')."</span></p>";

        $html .= "<div id='".$id."' class='mods-chrome'>";
            for($i=0; $i< $maxmods; $i++){
                $html .= "<div class='mods-chrome-boxs'>";
                    if($maxmods == 1) $modName = $modName;
                    else $modName = $name . '-' . ($i+1);

                    $html .= "<span class='pre-text'>". ucfirst($modName) ."</span>";
                    $html .= JHtml::_('select.genericlist', $options, '', '', 'value', 'text',$val[$modName],'');
                $html .= "</div>";
            }
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";


        $js = "
        jQuery('#$id select').change(function(){
            var valx = '';
            jQuery('#$id select option:selected').each(function(index,value){
                 valx += '$name'+ '-' + (index + 1) + ':' +  jQuery(this).val() + ',' ;
            });

            valx = valx.slice(0,-1);//trim , from end
            jQuery('#jform_params_$id').val(valx);
        });
    ";

        $expose->addjQDom($js);

        return $wrapstart . $html . $wrapend;

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