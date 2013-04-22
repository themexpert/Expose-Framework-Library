<?php
/**
 * @package     Expose
 * @version     ##VERSION##
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        layout.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//Import Module chrome generator class
expose_import('core.html');

//import joomla filesystem classes
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class ExposeLayout
{

    protected $modules          = array();
    protected $widgets          = array();
    protected $activeWidgets    = array();

    public function __construct()
    {
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

    public function renderModules($position, $inset=FALSE, $grid=NULL)
    {
        global $expose;

        $totalPublished = $this->modules[$position]['published'];
        $i = 1;

        if($totalPublished > 0 AND isset($this->modules[$position]['active']))
        {
            //check for inset position
            if($inset)
            {
                $grids = $this->getInsetModuleSchema($position,$grid);

            }else{
                $grids = $this->getModuleSchema($position);
            }

            $containerClass = 'grid';

            foreach($this->getActiveModuleLists($position) as $positionName)
            {
                //get the total published module/widget number for assigned position
                $totalModulesInPosition = $this->countModulesForPosition( $positionName );
                $totalWidgetsInPosition = $this->countWidgetsForPosition( $positionName );

                //get the grid number from grids array
                $grid = array_shift($grids);

                $class = '';
                $html = '';

                //Set the class first and last
                if($i == 1) $class .= 'first ';

                if($i == $totalPublished){
                    $class .= 'last ';
                }
                //Odd even class
                $class .= ($i%2) ? 'ex-odd' : 'ex-even';
                //set the grid class eg: grid6
                $grid = $containerClass . $grid;

                //we'll load all widgets first published in this position
                if($this->countWidgetsForPosition($positionName))
                {
                    foreach($this->activeWidgets[$positionName] as $widget)
                    {
                        $name = 'widget-' . $widget->name;
                        //get the widget content
                        $content = $widget->render();
                        // get widget specific html
                        $html .= ExposeHtml::widget($name, $content);
                    }
                }

                //if there is more then 1 module published we'll assign a identifier class
                if ( ( $totalModulesInPosition + $totalWidgetsInPosition ) > 1 )
                {
                    $class .= ' multi-module-column';
                }

                //now load modules content
                $chrome = $this->getModuleChrome($position, $positionName);

                $modWrapperStart = "<div class=\"$grid column $class $positionName\">";
                $modWrapperEnd = "</div>";



                switch ( $chrome ) {

                    case 'tabs':

                        $html .= ExposeHtml::tabs($positionName);
                        break;

                    case 'accordion':

                        $html .= ExposeHtml::accordion($positionName);
                        break;

                    case 'basic':

                        $html .= ExposeHtml::basic($positionName);
                        break;

                    default :

                        $html .= ExposeHtml::standard($positionName);
                        break;
                }

                //$html .= '<jdoc:include type="modules" name="'.$positionName.'" style="'.$chrome.'" />';
                //$html   .= '<jdoc:include type="modules" name="'.$positionName.'" style="'.$chrome.'" class="'.$class.'" positionName="'.$positionName.'" />';

                echo $modWrapperStart . $html . $modWrapperEnd;

                $i++;
            }
        }
    }

    protected function setModuleSchema($position)
    {
        global $expose;

        $values = $expose->get($position);
        $values = explode(',', $values);

        foreach($values as $value)
        {
            list($i, $v) = explode(':', "$value:");
            $this->modules[$position]['schema'][$i][] = $v;
        }
    }

    public function getModuleSchema($position)
    {
        if(!isset($this->modules[$position]))
        {
            return;
        }
        $published = $this->modules[$position]['published'];

        //return module schema based on active modules
        return $this->modules[$position]['schema'][$published];

    }

    public function getInsetModuleSchema($position, $grid)
    {
        //total module published in this position
        $total = $this->modules[$position]['published'];
        $used = 0;

        //set module schema
        $tempGrid = round($grid/$total);

        for( $i = 0; $i < $total; $i++ )
        {

            if( ($i+1) == $total )
            {

                $tempGrid = $grid - $used;
            }

            $this->modules[$position]['schema'][$total][$i] = $tempGrid;

            $used += $tempGrid ;
        }

        return $this->modules[$position]['schema'][$total];
    }

    public function getModuleChrome($position, $module)
    {
        if(!isset($this->modules[$position]['chrome']))
        {
            $this->setModuleChrome($position);
        }

        return $this->modules[$position]['chrome'][$module];
    }

    protected function setModuleChrome($position)
    {
        global $expose;

        $fieldName = $position . '-chrome';
        $data = $expose->get($fieldName);
        $data = explode(',', $data);

        foreach($data as $json)
        {
            list($modName, $chrome) = explode(':',$json);
            $this->modules[$position]['chrome'][$modName] = $chrome;
        }

    }

    public function getActiveModuleLists($position)
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

    public function getWidgetsForPosition($position)
    {
        if(!isset($this->widgets))
        {
            $this->loadWidgets();
        }

        $widgets = array();

        foreach($this->widgets as $name => $instance)
        {
            if($instance->isInPosition($position))
            {
                if(method_exists($instance, 'render'))
                {
                    if($instance->isEnabled()) 
                    {
                        $widgets[$name] = $instance;        
                    }    
                }
            }
        }

        return $widgets;
    }

    public function countWidgetsForPosition($position)
    {

        global $expose;
        $count = 0;
        $this->activeWidgets[$position] = array();

        if($expose->platform == 'mobile')
        {
            foreach($this->getWidgetsForPosition($position) as $widget)
            {
               if($widget->isInMobile())
               {
                   if(!in_array($widget, $this->activeWidgets[$position]))
                   {
                       $this->activeWidgets[$position][] = $widget;
                   }

                   $count++ ;
               }
            }


        }else{
            foreach ($this->getWidgetsForPosition($position) as $widget) {

                if(!in_array($widget, $this->activeWidgets[$position]))
                {
                    $this->activeWidgets[$position][] = $widget;
                }

                $count++;
            }

        }
        return $count;

        //return count($this->getWidgetsForPosition($position));
    }

    public function countModulesForPosition($position)
    {
        global $expose;

        $parentField = substr($position,0,strpos($position,'-')); //split the number and get the parent field name
        return $expose->document->countModules($position);

    }

    protected function loadWidgets()
    {
        global $expose;
        //define widgets paths
        $widgetPaths = array(
            $expose->exposePath . '/' . 'widgets',
            $expose->templatePath .'/' .'widgets'
        );
        $widgetLists = array();

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
                    $widgetLists[$widgetName] = $path;
                }
            }
        }
        ksort($widgetLists);

        foreach($widgetLists as $name => $path)
        {
            $className = 'ExposeWidget'. ucfirst($name);

            if(!class_exists($className) AND JFile::exists($path))
            {
                require_once($path);

                if(class_exists($className))
                {
                    $this->widgets[$name] = new $className();
                }
            }
        }

        //now initialize the widgets which is not position specific
        foreach($this->widgets as $name => $instance)
        {
            //we'll load the widgets based on platform permission
            if($expose->platform == 'mobile')
            {
                if($instance->isEnabled() AND $instance->isInMobile() AND method_exists($instance , 'init'))
                {
                    $instance->init();
                }
            }else{
                if( method_exists($instance, 'init') )
                {
                   if( $instance->isEnabled() ) 
                   {
                    $instance->init();
                   }
                }
            }
        }
    }

    public function renderBody()
    {
        global $expose;

        $layouts = (isset ($_COOKIE[$expose->templateName.'_layouts'])) ? $_COOKIE[$expose->templateName.'_layouts'] : $expose->get('layouts');

        if(isset ($_REQUEST['layouts'])){
            setcookie($expose->templateName.'_layouts',$_REQUEST['layouts'],time()+3600,'/');
            $layouts = $_REQUEST['layouts'];
        }

        $bPath = $expose->exposePath . DS . 'layouts';
        $tPath = $expose->templatePath . DS .'layouts';
        $ext = '.php';

        $bfile = $bPath .DS . $layouts . $ext;
        $tfile = $tPath .DS . $layouts . $ext;

        $this->loadFile(array($tfile,$bfile));

    }


    public function loadFile($paths)
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