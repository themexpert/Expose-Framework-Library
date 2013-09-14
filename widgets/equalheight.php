<?php
/**
 * @package     Expose
 * @version     4.0
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
        
        $js = '';

        $js .= "jQuery('#roof .column').equalHeight('.block');";
        $js .= "jQuery('#header .column').equalHeight('.block');";
        $js .= "jQuery('#top .column').equalHeight('.block');";
        $js .= "jQuery('#utility .column').equalHeight('.block');";
        $js .= "jQuery('#feature .column').equalHeight('.block');";
        $js .= "jQuery('#main-top .column').equalHeight('.block');";
        $js .= "jQuery('#content-top .column').equalHeight('.block');";
        $js .= "jQuery('#content-bottom .column').equalHeight('.block');";
        $js .= "jQuery('#main-bottom .column').equalHeight('.block');";
        $js .= "jQuery('#bottom .column').equalHeight('.block');";
        $js .= "jQuery('#footer .column').equalHeight('.block');";
        $js .= "jQuery('#mainbody, #sidebar-a, #sidebar-b').equalHeight();";

        $expose->addLink('equalheight.js','js');
        $expose->addjQDom($js);
    }
}

?>