<?php
/**
 * Common Functionality for Expose
 *
 * @package     Expose
 * @version     2.0  
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        common.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');
// ------------------------------------------------------------------------
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

// ------------------------------------------------------------------------

/**
* Class registry
*
* This function acts as a singleton.If it has
* previously been instantiated the variable is returned.
*
* @access	public
* @param	string	the class name being requested
* @param	string	the directory where the class should be found
* @param	string	the class name prefix
* @return	object
*/

function loadClass($class, $directory='core', $params='', $prefix='Expose'){
    static $_classes = array();
    //$base_path = realpath(dirname(__FILE__));

    //if directory path is NULL force it to core
    $directory = ($directory == '') ? 'core' : $directory;

    // Does the class exist?  If so, we're done...
    if(isset ($_classes[$class])){
        return $_classes[$class];
    }

    $name = FALSE;
    // Look for the class on given directory folder
    if(file_exists(EXPOSE_BASE . DS . $directory . DS . strtolower($class) .'.php')){
        $name = $prefix . $class;

        if(class_exists($name) === FALSE){
            require(EXPOSE_BASE . DS . $directory . DS . strtolower($class) .'.php');
        }
    }
    // Did we find the class?
    if ($name === FALSE)
    {
        // We use exit() rather then showing error in order to avoid a
        //self-referencing loop with the Excptions class
        exit('Unable to locate the specified class: '.  strtolower($class) . '.php in '. EXPOSE_BASE . $directory.' directory');
    }

    // Keep track of what we just loaded
    isLoaded($class);

    $_classes[$class] = new $name($params);
    return $_classes[$class];
}
// --------------------------------------------------------------------

/**
* Keeps track of which libraries have been loaded.  This function is
* called by the loadClass() function above
*
* @access	public
* @return	array
*/
function isLoaded($class = ''){
    static $_is_loaded = array();

    if ($class != '')
    {
        $_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
}
