<?php
/**
 * @package     Expose
 * @subpackage  Bootstrap Theme
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetJqueryfix extends ExposeWidget{
    
    public $name = 'jqueryfix';

    public function isEnabled()
    {
        return TRUE;
    }

    public function init()
    {
        global $expose;
        
        // Get the component name
        $option = JRequest::getCmd('option');
        
        /*Joomers fix . Joomer load jquery at the begining so we need to turn off expose jquery 
        */
        if( $option == 'com_jomres' )
        {
            // turn off loading expose jquery
            $expose->set('jquery-enabled', 0);
        }

        /*Virtuemart Fix*/
        if( $option == 'com_virtuemart')
        {
            // Expose jquery path
            $version = $expose->get('jquery-version', '1.8.3');
            $jqueryPath = $expose->exposeUrl . '/interface/js/jquery-' . $version .'.min.js';

            // Create script array to marge with joomla script array
            $js[$jqueryPath] = array( 'mime' => 'text/javascript' );
            // Marge with joomla script array
            $jsarray = array_merge($js, $expose->document->_scripts);
            // Unset it
            unset($expose->document->_scripts);
            // Inject Expose jquery at the end
            $expose->document->_scripts = $jsarray;

            //$field = $expose->app->getTemplate(true)->params;

            $expose->set('jquery-enabled', 0);
        }
    }
}

?>

