<?php
/**
 * Expose Main controller
 *
 * @package     Expose
 * @version     2.0   
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        core.php
 **/

expose_import('core.layout');
expose_import('core.typography');

class ExposeCore{

    //common var
    public  $baseUrl;
    public  $basePath;

    public  $templateUrl;
    public  $templatePath;

    public  $exposeUrl;
    public  $exposePath;

    public  $direction;
    public  $templateName;

    //Joomla Instance
    public $document;
    public $app;

    //style and scripts
    public  $styleSheets = array();
    //private  $styles = NULL;
    public  $scripts = array();
    private  $jqDom = NULL;
    private $prefix = '';

    //browser objects
    public $browser;
    public $platform;

    public function __construct(){
        //get the document object
        $this->document =& JFactory::getDocument();

        //get the application object
        $this->app =& JFactory::getApplication();

        //set the baseurl
        $this->baseUrl = JURI::root(true);


        //base path
        $this->basePath = JPATH_ROOT;

        //expose framework url and path based on Joomla version
        if(EXPOSE_JVERSION == '15'){
            $this->exposeUrl = $this->baseUrl . '/plugins/system/expose';
            $this->exposePath = $this->basePath . DS . 'plugins' . DS . 'system' . DS . 'expose';
        }else{
            $this->exposeUrl = $this->baseUrl . '/libraries/expose';
            $this->exposePath = $this->basePath . DS . 'libraries' . DS . 'expose';
        }

        //get the current template name
        $this->templateName = $this->getActiveTemplate();
        
        //template url
        $this->templateUrl = $this->baseUrl . '/templates/'. $this->templateName;


        //template path
        $this->templatePath = $this->basePath . DS . 'templates'. DS . $this->templateName ;

        //set document direction
        $this->direction = $this->setDirection();

        //detect the platform first
        $this->detectPlatform();

    }


    public static function getInstance()
    {
        static $instance;

        if(!isset($instance))
        {
            $instance = New ExposeCore;
        }

        return $instance;
    }

    public function isAdmin(){
        return $this->app->isAdmin();
    }

    public function finalizedExpose(){

        expose_import('core.processor');

        ExposeProcessor::process('css');
        ExposeProcessor::process('js');

        if(isset ($this->jqDom) AND $this->jqDom != NULL){
            $this->_renderCombinedDom();
        }
         //fix the template width and sidebar width
        $this->setCustomStyles();

        $typo = new ExposeTypography();
        $typo->renderFonts();

        define('EXPOSE_FINAL', 1);

    }

    //finalized Admin
    public function finalizedAdmin(){
        if($this->isAdmin()){
            expose_import('core.processor');

            ExposeProcessor::process('css');
            ExposeProcessor::process('js');

            $this->_renderCombinedDom();
        }
    }

    //public function to get template params
    public function get($params,$default=NULL){
        if(!$this->isAdmin()){
            $value = ($this->document->params->get($params) != NULL) ? $this->document->params->get($params) : $default;
            return $value;
        }
    }
    
    public function getActiveTemplate(){
        $app = JFactory::getApplication('site');
        return $app->getTemplate();
    }

    public function loadCoreStyleSheets()
    {
        if($this->isAdmin()) return;

        if($this->platform == 'desktop')
        {
            $files = array('expose.css','joomla.css');
            $this->addLink($files,'css',1);
        }else{
            $browser = strtolower($this->browser->getBrowser());
            $file = 'expose-'.$browser.'.css';
            $this->addLink($file,'css',1);

        }
        //load preset style
        $this->loadPresetStyle();
    }

    public function addLink($file, $type, $priority=10, $media='screen')
    {
        if(is_array($file)){
            foreach($file as $path){
                if($type == 'css')
                {
                    $this->addStyleSheet($path,$priority,$media);
                }else if($type == 'js'){
                    $this->addScript($path, $priority);
                }
            }
            return;
        }

        if($type == 'css')
        {
            $this->addStyleSheet($file,$priority,$media);
        }else if($type == 'js'){
            $this->addScript($file, $priority);
        }

        return;

    }

    private function addStyleSheet( $file, $priority, $media='screen' )
    {
        if(preg_match('/^http/', $file))
        {
            $url = $this->styleSheets[$priority]['url'][] = new stdClass();
            $url->media = $media;
            $url->url = $file;

            //$this->styleSheets[$priority]['url'][] = $file;
            return;
        }

        jimport('joomla.filesystem.file');

        $type = 'css';

        $burl = $this->exposeUrl . '/interface/' . $type . '/';
        $turl = $this->templateUrl  . '/' .$type . '/';

        $local = $this->styleSheets[$priority]['local'][] = new stdClass();

        if( dirname($file) != '.' AND dirname($file) != '..' )
        {
            //path is included so check its existence and add
            $path = $this->getFilePath($file);
            if(JFile::exists($path)){
                //$local = $this->styleSheets[$priority]['local'][] = new stdClass();
                $local->path = $path;
                $local->url = $file;
                $local->media = $media;
                //$this->styleSheets[$priority]['local'][$path] = $file;
            }

        }else{

            $tpath = $this->getFilePath($turl.$file);
            $bpath = $this->getFilePath($burl.$file);

            //$local = $this->styleSheets[$priority]['local'][] = new stdClass();
            //cross check both base and template path for this file
            if(JFile::exists($tpath))
            {
                //$this->styleSheets[$priority]['local'][] = $class;
                $local->url = $turl.$file;
                $local->path = $tpath;
                $local->media = $media;

            }elseif(JFile::exists($bpath)){
                $local->url = $burl.$file;
                $local->path = $bpath;
                $local->media = $media;
                //$this->styleSheets[$priority]['local'][$bpath] = $burl.$file;

            }
        }
    }

    private function addScript( $file, $priority)
    {
        if(preg_match('/^http/', $file))
        {
            $url = $this->scripts[$priority]['url'][] = new stdClass();
            $url->url = $file;

            return;
        }

        jimport('joomla.filesystem.file');

        $type = 'js';

        $burl = $this->exposeUrl . '/interface/' . $type . '/';
        $turl = $this->templateUrl  . '/' .$type . '/';

        $local = $this->scripts[$priority]['local'][] = new stdClass();

        if( dirname($file) != '.' AND dirname($file) != '..' )
        {

            //path is included so check its existence and add
            $path = $this->getFilePath($file);
            if(JFile::exists($path)){
                //$local = $this->styleSheets[$priority]['local'][] = new stdClass();
                $local->path = $path;
                $local->url = $file;
                return;
            }

        }else{

            $tpath = $this->getFilePath($turl.$file);
            $bpath = $this->getFilePath($burl.$file);

            //cross check both base and template path for this file
            if(JFile::exists($tpath))
            {
                //$this->styleSheets[$priority]['local'][] = $class;
                $local->url = $turl.$file;
                $local->path = $tpath;
                return;
            }elseif(JFile::exists($bpath)){
                $local->url = $burl.$file;
                $local->path = $bpath;
                return;
            }
        }
    }

    private function getFilePath($url)
    {
        $uri	    =& JURI::getInstance();
        $base	    = $uri->toString( array('scheme', 'host', 'port'));
        $path       = JURI::Root(true);
        if ($url && $base && strpos($url,$base)!==false) $url = preg_replace('|^'.$base.'|',"",$url);
        if ($url && $path && strpos($url,$path)!==false) $url = preg_replace('|^'.$path.'|',"",$url);
        if (substr($url,0,1) != DS) $url = DS.$url;
        $filepath = JPATH_SITE.$url;
        return $filepath;

    }

    public function loadPresetStyle(){

        if($this->isAdmin() OR $this->get('style') == '-1') return;

        //if(defined('EXPOSE_FINAL')) return;
        $preset_file = (isset ($_COOKIE[$this->templateName.'_style'])) ? $_COOKIE[$this->templateName.'_style'] : $this->get('style','style1');
        if(isset ($_REQUEST['style'])){
            setcookie($this->templateName.'_style',$_REQUEST['style'],time()+3600,'/');
            $preset_file = $_REQUEST['style'];
        }
        $preset_file = $preset_file.'.css';
        $this->addLink($preset_file, 'css');
    }


    public function addjQDom($js=NULL){
        if($js != NULL){
            $this->jqDom .= "\t\t\t" . $js ."\n";
        }
    }

    private function _renderCombinedDom()
    {
        $jqNoConflict = "\n\t\t".'jQuery.noConflict();'."\n";
        $dom = '';
        //add noConflict
        $dom .= $jqNoConflict;
        $dom .= "\n\t\t" . 'jQuery(document).ready(function($){'."\n".$this->jqDom."\n\t\t});";

        $this->document->addScriptDeclaration($dom);
    }

    public function addjQuery()
    {
        //come form admin? just add jquery without asking any question because jquery is heart of
        //expose admin
        if($this->isAdmin() AND !$this->app->get('jQuery')){
            $file = 'jquery-1.7.1.min.js';
            $this->app->set('jQuery','1.7.1');
            $this->addLink($file,'js',1);
            return;
        }
        
        if($this->get('jquery-enabled') AND !$this->app->get('jQuery')){
            $version = $this->get('jquery-version');
            //get the cdn
            $cdn = $this->get('jquery-source');
            switch ($cdn){
                case 'google-cdn':
                    $file = 'https://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js';
                    break;
                case 'local':
                    $file = 'jquery-'.$version.'.min.js';
                    break;
            }
            $this->app->set('jQuery',$version);
            $this->addLink($file,'js',2);
        }
        return;
    }

    private function setDirection()
    {
        if(defined('EXPOSE_FINAL')) return;
        if(isset ($_REQUEST['direction'])){
            setcookie($this->templateName.'_direction', $_REQUEST['direction'], time()+3600, '/');
            return $_REQUEST['direction'];
        }
        if(!isset($_COOKIE[$this->templateName.'_direction'])){
            if($this->document->direction == 'rtl' OR $this->get('rtl_enable')){
                return 'rtl';
            }else{
                return 'ltr';
            }
        }
        else{
            return $_COOKIE[$this->templateName.'_direction'];
        }
    }



    private function setCustomStyles()
    {
        if(defined('EXPOSE_FINAL')) return;
        $css = '';

        if($this->get('template-layout','fixed') == 'fixed' AND $this->platform != 'mobile'){

            /*$templWidth = (isset ($_COOKIE[$this->templateName.'_templateWidth'])) ? $_COOKIE[$this->templateName.'_templateWidth'] : $this->get('template-width');
            if(isset ($_REQUEST['templateWidth'])){
                setcookie($this->templateName.'_templateWidth',$_REQUEST['templateWidth'],time()+3600,'/');
                $templWidth = $_REQUEST['templateWidth'];
            }*/

            $width   = $this->get('template-width','980').'px';
            $css    .= '.ex-row{width:'.$width.'}';

        }

        if($this->get('custom-style-enabled') AND $this->platform != 'mobile')
        {
            $prefix = $this->getPrefix();

            $css .= "
                body{
                    background-color: #{$this->get('background-color')};
                    {$this->setBackgroundImage('background-image')}
                }

                .ex-header .ex-title{
                    color: #{$this->get('module-title-color')}
                }

                #{$prefix}main #ex-content .ex-title,
                #{$prefix}main #ex-content .ex-title a{
                    color: #{$this->get('article-title-color')}
                }

                #{$prefix}header{
                    background-color: #{$this->get('header-bg-color')};
                    {$this->setBackgroundImage('header-image')}
                    color: #{$this->get('header-text-color')};
                }
                #{$prefix}header a{
                    color: #{$this->get('header-link-color')};
                }
                #{$prefix}header a:hover{
                    color: #{$this->get('header-link-hover-color')};
                }

                #{$prefix}top{
                    background-color: #{$this->get('top-bg-color')};
                    {$this->setBackgroundImage('top-image')}
                    color: #{$this->get('top-text-color')};
                }
                #{$prefix}top a{
                    color: #{$this->get('top-link-color')};
                }
                #{$prefix}top a:hover{
                    color: #{$this->get('top-link-hover-color')};
                }

                #{$prefix}feature{
                    background-color: #{$this->get('feature-bg-color')};
                    {$this->setBackgroundImage('feature-image')}
                    color: #{$this->get('feature-text-color')};
                }
                #{$prefix}feature a{
                    color: #{$this->get('feature-link-color')};
                }
                #{$prefix}feature a:hover{
                    color: #{$this->get('feature-link-hover-color')};
                }

                #{$prefix}main #ex-content{
                    background-color: #{$this->get('maincontent-bg-color')};
                    {$this->setBackgroundImage('maincontent-image')}
                    color: #{$this->get('maincontent-text-color')};
                }
                #{$prefix}main #ex-content a{
                    color: #{$this->get('maincontent-link-color')};
                }
                #{$prefix}main #ex-content a:hover{
                    color: #{$this->get('maincontent-link-hover-color')};
                }

                #{$prefix}bottom{
                    background-color: #{$this->get('bottom-bg-color')};
                    {$this->setBackgroundImage('bottom-image')}
                    color: #{$this->get('bottom-text-color')};
                }
                #{$prefix}bottom a{
                    color: #{$this->get('bottom-link-color')};
                }
                #{$prefix}bottom a:hover{
                    color: #{$this->get('bottom-link-hover-color')};
                }

                #{$prefix}footer{
                    background-color: #{$this->get('footer-bg-color')};
                    {$this->setBackgroundImage('footer-image')}
                    color: #{$this->get('footer-text-color')};
                }
                #{$prefix}footer a{
                    color: #{$this->get('footer-link-color')};
                }
                #{$prefix}footer a:hover{
                    color: #{$this->get('footer-link-hover-color')};
                }

            ";
        }

        $this->addInlineStyles($css);
    }

    public function setPrefix($name)
    {
        $this->prefix = $name;
    }

    public function getPrefix()
    {
        if($this->prefix == '')
        {
            $this->setPrefix('ex-');
        }

        return $this->prefix;
    }

    public function setBackgroundImage($param, $dir='backgrounds')
    {
        $image = $this->get($param);
        $repeat = $this->get($param.'-repeat','repeat');
        $css = '';

        if($image == -1) return;

        $path = $this->templateUrl . '/images/'. $dir .'/' . $image;
        $css  .= "background-image: url({$path});";
        $css .= "background-repeat: $repeat;";

        return $css;
    }

    public function addInlineStyles($content){
        $this->document->addStyleDeclaration($content);
    }


    public function displayHead(){
        if(defined('EXPOSE_FINAL')) return;
        if(!$this->isAdmin()){
            //output joomla head
            echo '<jdoc:include type="head" />';
            $this->document->setMetaData('viewport','width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1');
        }
    }

    public function generateBodyClass()
    {
        $menu = $this->app->getMenu();
        $active = $menu->getActive();
        $class  = NULL;
        $component = str_replace('_','-', JRequest::getCmd('option'));
        $view = JRequest::getCmd('view');
        $class .= ($this->get('style') == '-1') ? 'style-none' : $this->get('style');
        $class .= ' align-'.$this->direction;
        $class .= ' page-id-'. (isset($active) ? $active->id : $menu->getDefault()->id);
        $class .= ' '.$component . '-' . $view;
        $class .= ' '. $this->get('layout-type');
        $class .= ' layout-'. $this->get('template-layout');
        $class .= ' ' . strtolower($this->browser->getBrowser());
        $class .= ($this->displayComponent()) ? '' : ' com-disabled';

        return 'class="'.$class.'"';
    }
    
    public function displayComponent(){

        if($this->get('component-disable'))
        {
            $ids = $this->get('component-disable-menu-ids');

            if(!empty($ids))
            {
                $menuIds = explode(',',$ids);
                $currentMenuId = JRequest::getInt('Itemid');
                if(in_array($currentMenuId, $menuIds))
                {
                    return FALSE;

                }else{
                    return TRUE;
                }
            }else{
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }

    public function getSidebarsWidth($position)
    {
        $width = array();
        $layout = ExposeLayout::getInstance();
        $width = $layout->getModuleSchema($position);
        return $width[0];

    }

    public function getComponentWidth()
    {
        $widths = array();
        $layout = ExposeLayout::getInstance();
        $widths['a'] = 0;
        $widths['b'] = 0;
        $widths['component'] = 0;

        if($layout->countModulesForPosition('sidebar-a') OR $layout->countWidgetsForPosition('sidebar-a'))
        {
            $width = explode(':',$this->get('sidebar-a'));
            $widths['a'] = $width[1];

        }

        if($layout->countModulesForPosition('sidebar-b') OR $layout->countWidgetsForPosition('sidebar-b'))
        {
            $width = explode(':',$this->get('sidebar-b'));
            $widths['b'] = $width[1];
        }

        $mainBodyWidth = 100 - ($widths['a'] + $widths['b']);

        $width['component']= $mainBodyWidth;
        $width['sidebar-a'] = $widths['a'];
        $width['sidebar-b'] = $widths['b'];

        return $width;

    }

    public function countModules($position)
    {
        $layout = ExposeLayout::getInstance();

        return $layout->countModules($position);
    }

    public function renderModules($position)
    {
        $layout = ExposeLayout::getInstance();
        $layout->renderModules($position);
    }

    public function renderBody()
    {
        $layout = ExposeLayout::getInstance();
        $layout->renderBody();
    }

    public function detectPlatform()
    {
        expose_import('libs.browser');
        $this->browser = new ExposeBrowser();
        $browserName = $this->browser->getBrowser();

        //we'll consider 2 mobile now iPhone and Android, iPad will treat as regular desktop device
        if($this->get('iphone-enabled') AND $this->browser->isMobile() AND $browserName == 'iPhone')
        {
            $this->platform = 'mobile';

        }elseif($this->get('android-enabled') AND $this->browser->isMobile() AND $browserName == 'android'){

            $this->platform = 'mobile';

        }else{
            $this->platform = 'desktop';
        }

    }
}