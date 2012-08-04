<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        modernizr.php
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetModernizr extends ExposeWidget{

    public $name = 'modernizr';

    public function isInMobile()
    {
        return FALSE;
    }

    public function init()
    {
        global $expose;
        $expose->addLink($expose->exposeUrl.'/interface/js/modernizr-1.7.min.js','js');

    }
}

?>

