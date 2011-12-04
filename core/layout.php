<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        layout.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import joomla filesystem classes
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//import core class
expose_import('core.core');

class ExposeLayout extends ExposeCore
{

    protected $modules = array();
    public $widgets = array();


    public function __construct()
    {
        parent::__construct();

        //load all widgets in an array and trigger the initialize event for those widgets.
        $this->loadWidgets();

    }

    public static function getInstance()
    {
        static $instance;

        if(!isset($instance))
        {
            $instance = New ExposeLayout;
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

        //check orphan module position which have nos subset.
        if($this->countModulesForPosition($position) OR $this->countWidgetsForPosition($position))
        {
            //set this position in active array record
            $this->modules[$position]['active'][] = $position;
            $published ++;
            $this->modules[$position]['published'] = $published;
            return TRUE;
        }

        //loop through all module-position(eg: roof-1, roof-2) and set the total published module num.
        foreach($this->modules[$position]['schema'] as $num => $v)
        {
            $positionName = ($position . '-' . $num) ;

            if($this->countModulesForPosition($positionName) OR $this->countWidgetsForPosition($positionName))
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

        if($this->modules[$position]['published'] > 0 AND isset($this->modules[$position]['active']))
        {
            $widths = $this->getModuleSchema($position);

            foreach($this->getActiveModuleLists($position) as $positionName)
            {
                $width = array_shift($widths);

                //we'll make all width 100% for mobile device
                if($this->platform == 'mobile'){
                    $width = 100;
                }

                if($i == 1) $class = 'first';
                elseif($i == $this->modules[$position]['published']) $class = 'last';
                else $class = '';

                $oddEven = ($i%2) ? 'odd' : 'even';

                $modWrapperStart = "<div class='ex-mods $oddEven $class' style='width:" . $width ."%'>";
                $modWrapperEnd = "</div>";
                $html = '';

                //we'll load all widgets first published in this position
                if($this->countWidgetsForPosition($positionName))
                {
                    foreach($this->getWidgetsForPosition($positionName) as $gist)
                    {
                        $html .= $gist->render();
                    }
                }

                //now load modules content
                $chrome = $this->getModuleChrome($position,$positionName);

                $html .= '<jdoc:include type="modules" name="'.$positionName.'" style="'.$chrome.'" />';


                echo $modWrapperStart . $html . $modWrapperEnd;

                $i++;
            }
        }
    }

    protected function setModuleSchema($position)
    {
        $values = $this->get($position);
        $values = explode(',', $values);

        foreach($values as $value)
        {
            list($i, $v) = explode(':', "$value:");
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

    public function getWidget($name)
    {
        if(isset($this->widgets[$name]))
        {
            return $this->widgets[$name];
        }

        return FALSE;
    }

    protected function getWidgetsForPosition($position)
    {
        if(!isset($this->widgets))
        {
            $this->loadWidgets();
        }

        $widgets = array();

        foreach($this->widgets as $name => $instance)
        {
            if($instance->isEnabled() AND $instance->isInPosition($position) AND method_exists($instance, 'render')){

                $widgets[$name] = $instance;
            }
        }

        return $widgets;
    }

    protected function countWidgetsForPosition($position)
    {

        if($this->platform == 'mobile')
        {
            $count = 0;

            foreach($this->getWidgetsForPosition($position) as $gist)
            {
               ($gist->isInMobile()) ? $count++ : '';
            }

            return $count;
        }

        return count($this->getWidgetsForPosition($position));
    }

    protected function countModulesForPosition($position)
    {
        $parentField = substr($position,0,strpos($position,'-')); //split the number and get the parent field name

        if($this->platform == 'mobile')
        {
            if($this->get($parentField.'-mobile'))
            {
                return $this->document->countModules($position);
            }else{
                return FALSE;
            }
        }

        return $this->document->countModules($position);

    }

    protected function loadWidgets()
    {
        //define widgets paths
        $widgetPaths = array(
            $this->exposePath . DS . 'widgets',
            $this->templatePath . DS .'widgets'
        );
        //first loop through all the template and framework path and take widget instance
        foreach($widgetPaths as $widgetPath)
        {
            $widgets = JFolder::files($widgetPath, '.php');

            if(is_array($widgets))
            {
                foreach($widgets as $widget)
                {
                    $widgetName = JFile::stripExt($widget);
                    $path = $widgetPath . DS . $widgetName .'.php';
                    $className = 'ExposeWidget'. ucfirst($widgetName);

                    if(!class_exists($className) AND JFile::exists($path))
                    {
                        require_once($path);

                        if(class_exists($className))
                        {
                            $this->widgets[$widgetName] = new $className();
                        }
                    }

                }
            }
        }

        //now initialize the widgets which is not position specific
        foreach($this->widgets as $name => $instance)
        {
            //we'll load the widgets based on platform permission
            if($this->platform == 'mobile')
            {
                if($instance->isEnabled() AND $instance->isInMobile() AND method_exists($instance , 'init'))
                {
                    $instance->init();
                }
            }else{
                if($instance->isEnabled() AND method_exists($instance, 'init'))
                {
                    $instance->init();
                }
            }
        }
    }

    public function renderBody()
    {
        $layoutType = $this->get('layout-type');
        $bPath = $this->exposePath . DS . 'layouts';
        $tPath = $this->templatePath . DS .'layouts';
        $ext = '.php';

        if( $this->platform == 'mobile' )
        {
            $device = strtolower($this->browser->getBrowser());
            $bfile = $bPath .DS . $device . $ext;
            $tfile = $tPath .DS . $device . $ext;

            if($this->get('iphone-enabled') AND $device == 'iphone')
            {
                $this->loadFile(array($tfile,$bfile));
            }elseif($this->get('android-enabled') AND $device == 'android'){
                $this->loadFile(array($tfile,$bfile));
            }else{
                return FALSE;
            }

        }else{
            $bfile = $bPath .DS . $layoutType . $ext;
            $tfile = $tPath .DS . $layoutType . $ext;

            $this->loadFile(array($tfile,$bfile));
        }
    }

    protected function loadFile($paths)
    {
        if(is_array($paths))
        {
            foreach($paths as $path)
            {
                if(JFile::exists($path)){

                    require_once($path);
                    break;

                }
            }
        }else if(JFile::exists($paths))
        {
            require_once ($paths);
        }else{
            JError::raiseNotice(E_NOTICE,"No file file found on given path $paths");
        }
    }

    public function getModules()
    {
        return $this->modules;
    }

}