<?php
/**
 * Bootstrap for Expose
 *  
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        bootstrap.php
 **/
// No direct access.
defined('_JEXEC') or die;

if(!defined('EXPOSE_VERSION')){
    //define framework version.
    define('EXPOSE_VERSION', '2.0');

    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    //Defines
    if(version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
        define('EXPOSE_BASE', JPATH_SITE.DS.'plugins'.DS.'system'.DS.'expose');
    }else{
        define('EXPOSE_BASE', JPATH_SITE.DS.'libraries'.DS.'expose');
    }
    
    define('EXPOSE_GISTS_PATH', EXPOSE_BASE.DS.'gists');
    define('EXPOSE_LAYOUT_PATH', EXPOSE_BASE.DS.'layouts');

    global $expose;
    
    //include common functiionality
    require_once 'common.php';

    expose_import('core.expose');
    
    $expose =& loadClass('Expose','','','');

}