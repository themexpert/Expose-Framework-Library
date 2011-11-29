<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        layouts.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import core class
expose_import('core.core');

class ExposeLayouts extends ExposeCore{

    public $browser;
    public $platform;
    public $modules = array();
    public $gists = array();


    public function __construct()
    {
        parent::__construct();

        $this->loadGists();

        $this->detectPlatform();

        //echo "<pre>";print_r($this->modules); echo "</pre>";
    }

    public static function getInstance()
    {
        static $instance;

        if(!isset($instance))
        {
            $instance = New ExposeLayouts;
        }

        return $instance;
    }


    public function countModules($position)
    {
        //check if the module schema already exist for this position return it back.
        //if not exist set if first
        if(!isset($this->modules[$position]['schema']))
        {
            $this->setModuleSchema($position);
        }

        $published = 0;

        //loop through all sub-position(eg: roof-1, roof-2) and set the total published module num.
        foreach($this->modules[$position]['schema'] as $num => $v)
        {
            $positionName = ($position . '-' . $num) ;

            if($this->document->countModules($positionName) OR $this->countGistsForPosition($positionName))
            {
                //set this position in active array record
                $this->modules[$position]['active'][] = $positionName;

                $published ++;
            }

        }
        $this->modules[$position]['published'] = $published;

        if($published > 0) return TRUE;

        return FALSE;

    }

    public function renderModules($position)
    {
        $class = 'first';
        $i = 1;

        foreach($this->getActiveModuleLists($position) as $positionName)
        {
            //we'll load all gists first published in this position
            foreach($this->getGistsForPosition($positionName) as $gist)
            {
               $gist->render();
            }
            //now load modules content
            $width = array_shift($this->getModuleSchema($position));
            $chrome = $this->getModuleChrome($position,$positionName);

            echo '<jdoc:include type="modules" name="'.$positionName.'" style="'.$chrome.'" width="'.$width.'" class="'.$class.'" />';
            $class = ($i == $this->modules[$position]['published']) ? 'last':'';

            $i++;
        }

    }

    protected function setModuleSchema($position)
    {
        $values = $this->get($position);
        $values = explode(',', $values);

        foreach($values as $value)
        {
            list($i, $v) = explode(':', $value);
            $this->modules[$position]['schema'][$i][] = $v;
        }

    }

    protected function getModuleSchema($position)
    {
        $published = $this->modules[$position]['published'];

        //return module schema based on active modules
        return $this->modules[$position]['schema'][$published];

    }

    protected function getModuleChrome($position, $module)
    {
        if(!isset($this->modules[$position]['chrome']))
        {
            $this->setModuleChrome($position);
        }

        return $this->modules[$position]['chrome'][$module];
    }

    protected function setModuleChrome($position)
    {
        $fieldName = $position . '-chrome';
        $data = $this->get($fieldName);
        $data = explode(',', $data);
        foreach($data as $json)
        {
            list($modName, $chrome) = explode(':',$json);
            $this->modules[$position]['chrome'][$modName] = $chrome;
        }

    }

    protected function getActiveModuleLists($position)
    {
        //return active module array associate with position
        return $this->modules[$position]['active'];

    }

    public function getGist($name)
    {
        if(isset($this->gists[$name]))
        {
            return $this->gists[$name];
        }

        return FALSE;
    }

    protected function getGistsForPosition($position)
    {
        $gists = array();

        foreach($this->gists as $name => $instance)
        {
            if($instance->isEnabled() AND $instance->isInPosition($position) AND method_exists($instance, 'render')){

                $gists[$name] = $instance;
            }
        }

        return $gists;
    }
    protected function countGistsForPosition($position)
    {
        if(!isset($this->gists)){
            $this->loadGists();
        }

        $count = 0;

        foreach($this->gists as $name => $instance)
        {
            if($instance->isEnabled() AND $instance->isInPosition($position) AND method_exists($instance, 'render')){

                $count ++;
            }
        }
        return $count;

    }

    protected function loadGists()
    {
        //import joomla filesystem classes
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        //define gist paths
        $gistPaths = array(
            $this->exposePath . DS . 'gists',
            $this->templatePath . DS .'gists'
        );
        //first loop through all the template and framework path and take gist instance
        foreach($gistPaths as $gistPath)
        {
            $gists = JFolder::files($gistPath, '.php');

            if(is_array($gists))
            {
                foreach($gists as $gist)
                {
                    $gistName = JFile::stripExt($gist);
                    $path = $gistPath . DS . $gistName .'.php';
                    $className = 'ExposeGist'. ucfirst($gistName);

                    if(!class_exists($className) AND JFile::exists($path))
                    {
                        require_once($path);

                        if(class_exists($className))
                        {
                            $this->gists[$gistName] = new $className();
                        }
                    }

                }
            }
        }

        //now initialize the gists which is not position specific
        foreach($this->gists as $name => $instance)
        {
            if($instance->isEnabled() AND method_exists($instance, 'init'))
            {
                $instance->init();
            }
        }
    }

    public function detectPlatform()
    {
        expose_import('libs.browser');
        $this->browser = new ExposeBrowser();

        if($this->browser->isMobile()){
            $this->platform = 'mobile';
        }else{
            $this->platform = 'desktop';
        }

    }

    protected function displayModules($position)
    {

    }

}