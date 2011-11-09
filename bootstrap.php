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

    /* Define framework version.
     * Rule of version number should be followed by X.Y.Z
     * which generally corresponds to major.minor.patch
     */

    define('EXPOSE_VERSION', '3.0.0');

    //define directory separator
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    //Expose base path will depend on Joomla version
    if(version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.7', '<')){
        //define joomla version for further use
        define('EXPOSE_JVERSION','15');
        //define expose base path
        define('EXPOSE_BASE', JPATH_SITE.DS.'plugins'.DS.'system'.DS.'expose');
    }else{
        define('EXPOSE_JVERSION','17');
        define('EXPOSE_BASE', JPATH_SITE.DS.'libraries'.DS.'expose');
    }
    //Gists Framework path
    define('EXPOSE_GISTS_PATH', EXPOSE_BASE.DS.'gists');

    //Layouts Framwork path
    define('EXPOSE_LAYOUT_PATH', EXPOSE_BASE.DS.'layouts');

    //declare global ver
    global $expose;
    
    //include common functionality for framework
    require_once 'common.php';

    expose_import('core.expose');
    
    $expose =& loadClass('Core');

}