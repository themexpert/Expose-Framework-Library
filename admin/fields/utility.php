<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 15, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        utility.php
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldUtility extends JFormField{

    protected  $type = 'Utility';

    protected function getInput(){

        //load expose bootstrap
        require_once (JPATH_LIBRARIES.DS.'expose'.DS.'expose.php');

        global $expose;
        // Initialize some field attributes.
        $action     = $this->element['action'];
        $class		= (string) $this->element['class'];
        $html = '';

        if($action == 'boot'){
            $expose->addLink($expose->exposeUrl.'/interface/js/jquery.uniform.min.js','js');
            $expose->addLink($expose->exposeUrl.'/interface/js/jquery.cookie.js','js');

            //load expose.css file
            $expose->addLink($expose->exposeUrl.'/admin/widgets/expose.css','css');
        }
        else if($action == 'finalize'){
            //load main expose js file

            $expose->addLink($expose->exposeUrl.'/admin/widgets/jquery.tools.min.js','js');
            $expose->addLink($expose->exposeUrl.'/admin/widgets/expose.js','js');
            
            //finalize addmin
            $expose->finalizedAdmin();
        }
        $html .= "<input type='hidden' class='$class'>";
        return $html;
    }
}


