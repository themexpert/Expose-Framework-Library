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

class ExposeWidgetLazyLoader extends ExposeWidget{

    public $name = 'lazyloader';

    public function isInMobile()
    {
        return FALSE;
    }

    public function init()
    {
        global $expose;
        $js ='jQuery(\'img\').lazyload({effect: "fadeIn",threshold : 100});';
        $expose->document->addScript($expose->exposeUrl.'/interface/js/lazyload.js');
        $expose->addjQDom($js);
    }
}
?>
