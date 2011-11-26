<?php
/**
 * Bootstrap for Expose
 *  
 * @package     Expose
 * @version     3.0.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Check for PHP4
if(defined('PHP_VERSION')) {
	$version = PHP_VERSION;
} elseif(function_exists('phpversion')) {
	$version = phpversion();
} else {
	// No version info. I'll lie and hope for the best.
	$version = '5.0.0';
}

// Old PHP version detected. EJECT! EJECT! EJECT!
if(!version_compare($version, '5.0.0', '>='))
{
	return JError::raise(E_ERROR, 500, 'PHP 4 is not supported by Expose Framework');
}

if(!defined('EXPOSE_VERSION'))
{

    // Define framework version.

    define('EXPOSE_VERSION', '3.0.0');

    //define directory separator
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    //Expose base path will depend on Joomla version
    if(version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.7', '<')){
        //define joomla version for further use
        define('EXPOSE_JVERSION','15');
    }else{
        define('EXPOSE_JVERSION','17');
    }

    //declare global ver
    global $expose;

    $app =& JFactory::getApplication();

    if($app->isAdmin())
    {
        expose_import('core.core');
        $expose = new ExposeCore();
    }else{
        
        expose_import('core.layouts');
        $expose = new ExposeLayouts();
    }

}

/**
* File Loader
*
* This function will load file form given paths. Joomla default path style
*
* @access	public
* @param	string	the directory path
* @return	void
*/

function expose_import($paths){
    $paths = str_replace('.', DS, $paths);
    $file = realpath(dirname(__FILE__)).DS.$paths.'.php';
    if(file_exists($file))    include_once ($file);
}