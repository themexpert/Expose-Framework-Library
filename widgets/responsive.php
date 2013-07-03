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

class ExposeWidgetResponsive extends ExposeWidget{

    public $name = 'responsive';

    public function isEnabled()
    {
        return TRUE;
    }


    public function init()
    {
        global $expose;
        
        // Set the viewport meta
        $expose->document->setMetaData('viewport','width=device-width, initial-scale=1.0');

        // Add breakpoint js
        $expose->addLink('breakpoints.js','js');
    }
}

?>