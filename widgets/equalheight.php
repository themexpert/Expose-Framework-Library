<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetEqualheight extends ExposeWidget{

    public $name = 'equalheight';



    public function init()
    {
        global $expose;
        $prefix = $expose->getPrefix();
        $js = '';

        $js .= "jQuery('#{$prefix}roof .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}header .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}top .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}utility .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}feature .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}main-top .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}content-top .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}content-bottom .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}main-bottom .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}bottom .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}footer .ex-column').equalHeight('.ex-block');";
        $js .= "jQuery('#{$prefix}mainbody, #{$prefix}sidebar-a, #{$prefix}sidebar-b').equalHeight();";

        $expose->addLink('jquery.equalheight.js','js');
        $expose->addjQDom($js);
    }
}

?>