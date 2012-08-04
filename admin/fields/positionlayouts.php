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


class JFormFieldPositionLayouts extends JFormField{

    protected $type = 'PositionLayouts';

    protected function getInput(){
        global $expose;

        $maxmods = (int) $this->element['max-mods'];
        $html = '';

        $id = str_replace('jform_params_','',$this->id);
        $finalValue = array();

        if(!empty($this->value)){
            $tempVal = explode(',',$this->value);

            foreach($tempVal as $value){
                list($index, $val) = explode(':',$value);
                $finalValue[$index][] = $val;
            }
        }


        $html .= "<div id='mods-wrap' data-mods='".$maxmods."'>";
            $html .= "<div class='mod-tabs'>";
                $html .= "<ul>";
                for($nav=1; $nav<=$maxmods; $nav++){
                    $html .= "<li><a>$nav</a></li>";
                }
                $html .= "</ul>";

            $html .= "<div id='$id' class='mod-inputs'>";
            for($pan=1; $pan<=$maxmods; $pan++){
                $html .= "<div class='inputs'>";
                    for($input=0; $input<$pan; $input++){
                        if(empty($this->value)){
                            $val = 100/$pan;
                            if(is_float($val)) $val = sprintf("%01.2f", 100/$pan);
                            $finalValue[$pan][$input] = $val;
                        }
                        //echo $finalValue[1][1] , ',';
                        $html .= "<input type='text' class='inputbox' value='".($finalValue[$pan][$input])."' /><span>%</span>";
                    }
                $html .= "</div>";
            }
            $html .= "</div>";

            $html .= "</div>";
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";

        $js = "
        jQuery('#$id .inputbox').bind('keyup',function(){
        var valx = '';
        jQuery('#$id .inputs').each(function(index1,value1){
            jQuery(this).find('.inputbox').each(function(index2,value2){
                 valx += (index1 + 1) + ':' +  jQuery(this).val() + ',' ;
            });
        });
        valx = valx.slice(0,-1);//trim , from end
        jQuery('#jform_params_$id').val(valx);
    });";
        $expose->addjQDom($js);
        return $html;

    }


}