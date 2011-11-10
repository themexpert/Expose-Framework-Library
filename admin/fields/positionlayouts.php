<?php
/**
 * Created by JetBrains PhpStorm.
 * User: codexpert
 * Date: 10/26/11
 * Time: 9:37 PM
 * To change this template use File | Settings | File Templates.
 */

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldPositionLayouts extends JFormField{

    protected $type = 'PositionLayouts';

    protected function getInput(){

        $maxmods = (int) $this->element['max-mods'];
        $html = '';

        $position_name = str_replace('jform_params_','',$this->id);

        $html .= "<div id='mods-wrap' data-mods='".$maxmods."'>";
            $html .= "<div class='mod-tabs'>";
                $html .= "<ul>";
                for($nav=1; $nav<=$maxmods; $nav++){
                    $html .= "<li><a>$nav</a></li>";
                }
                $html .= "</ul>";

            $html .= "<div id='$position_name' class='mod-inputs'>";
            for($pan=1; $pan<=$maxmods; $pan++){
                $html .= "<div class='inputs'>";
                    $val = 100/$pan;
                    if(is_float($val)) $val = sprintf("%01.2f", 100/$pan);

                    for($input=1; $input<=$pan; $input++){
                        $html .= "<input type='text' class='inputbox' value='".($val)."' /><span>%</span>";
                    }
                $html .= "</div>";
            }
            $html .= "</div>";

            $html .= "</div>";
        $html .= "</div>";

        $html .= "<input type='hidden' name='".$this->name."' id='".$this->id."' value='".$this->value."' />";
        return $html;

    }


}