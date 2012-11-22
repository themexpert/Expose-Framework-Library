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

class ExposeWidgetWordformat extends ExposeWidget{

    public $name = 'wordformat';

    public function init()
    {
        global $expose;

        $selector    = (string) $this->get('selector');

        $js = "jQuery('$selector').lettering('words');";

        $expose->addLink('jquery.lettering.js', 'js');
        $expose->addjQDom($js);

    }
}