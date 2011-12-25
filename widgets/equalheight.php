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

    public function isInMobile()
    {
        return TRUE;
    }

    public function init()
    {
        global $expose;
        $js = 'equalHeight(jQuery(".ex-box"));';
        $expose->document->addScript($expose->exposeUrl.'/interface/js/jquery.equalheight.js');
        $expose->addjQDom($js);
    }
}

?>