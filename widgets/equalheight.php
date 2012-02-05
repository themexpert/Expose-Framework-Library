<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetEqualHeight extends ExposeWidget{

    public $name = 'equalheight';

    public function init()
    {
        global $expose;
        $js = '';
        $js .= "$('#ex-roof .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-header .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-top .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-utility .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-feature .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-maintop .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-contenttop .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-contentbottom .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-mainbottom .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-bottom .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-footer .ex-column').equalHeight('.ex-block');";
        $js .= "$('#ex-mainbody, #ex-sidebar-a, #ex-sidebar-b').equalHeight();";

        $expose->addLink('jquery.equalheight.js','js');
        $expose->addjQDom($js);
    }
}

?>