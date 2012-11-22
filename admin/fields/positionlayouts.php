<?php
/**
 * @package     Expose
 * @version     4.0
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
        $grid = 12;

        $id                 = str_replace('jform_params_','',$this->id);
        $finalValue         = array();
        $tempDefaultVaule   = array();

        if(!empty($this->value)){
            $tempVal        = explode(',',$this->value);

            /*
             * Take the value from xml and convert it to array
             *
             * @deprecated : 4.0
             **/
            foreach($tempVal as $value){
                list($index, $val) = explode(':',$value);
                $finalValue[$index][] = $val;
            }

            /*
             * Backward compatibility
             * This code will check and compare with default value, if find old % based value replace with
             * grid value
             *
             **/
            $tempDefault    = explode(',',$this->element['default']);

            foreach($tempDefault as $value){
                list($index, $val) = explode(':',$value);
                $tempDefaultVaule[$index][] = $val;
            }

            //compare with default value with old value
            foreach( $tempDefaultVaule as $index => $val )
            {
                if( is_array( $val ) ){
                    foreach( $val as $key => $v )
                    {

                        if( !isset($finalValue[$index][$key]) OR $finalValue[$index][$key] > 12 ){

                            $finalValue[$index][$key] = $tempDefaultVaule[$index][$key];
                        }
                    }
                }
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
                            $val = $grid/$pan;
                            //if(is_float($val)) $val = sprintf("%01.2f", 100/$pan);
                            $finalValue[$pan][$input] = $val;
                        }
                        //echo $finalValue[1][1] , ',';
                        $html .= "<input type='text' class='inputbox' value='".($finalValue[$pan][$input])."' />";
                    }
                $html .= "</div>";
            }
            $html .= "</div>";

            $html .= "</div>";
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";

        $js = "
        // Expose 3.0 Backward compatibility code
        var o = jQuery('#jform_params_$id').val(),
            n = o.split(','),
            f = n[0].split(':'),
            g = '1:12,2:6,2:6,3:4,3:4,3:4,4:3,4:3,4:3,4:3,5:3,5:2,5:2,5:2,5:3,6:2,6:2,6:2,6:2,6:2,6:2';
        //assuming that u have old value
        if( f[1] >= 100 )
        {
           jQuery('#jform_params_$id').val(g);
        }

        jQuery('#$id .inputbox').bind('keyup',function(){
        var valx = '';
        jQuery('#$id .inputs').each(function(index1,value1){
            jQuery(this).find('.inputbox').each(function(index2,value2){
                 valx += (index1 + 1) + ':' +  jQuery(this).val() + ',' ;
            });
        });
        valx = valx.slice(0,-1);//trim [,] from end
        jQuery('#jform_params_$id').val(valx);
    });";
        $expose->addjQDom($js);
        return $html;

    }


}