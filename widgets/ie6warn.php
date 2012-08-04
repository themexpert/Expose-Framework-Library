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

class ExposeWidgetIe6warn extends ExposeWidget{

    public $name = 'ie6warn';

    public function isInMobile()
    {
        return FALSE;
    }

    public function init()
    {
        global $expose;

        if($expose->browser->getBrowser() == ExposeBrowser::BROWSER_IE AND $expose->browser->getVersion() == 6)
        {
            //add ie6warn js
            $expose->addLink('ie6warn.js','js');
            //add js to onload method
            $expose->document->addScriptDeclaration('window.onload=sevenUp.plugin.black.test( options, callback );');
        }
    }
}