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
    private  $styleSheets = array();
    private  $styles = NULL;
    private  $scripts = array();
    private  $jqDom = NULL;

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
        $this->direction = $this->_setDirection();

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

        if($this->get('compress_css',0)){
            ExposeCompressor::compressCSS();
        }else{
            //load all styles sheet from quee
            $this->_loadStyles();
        }
        if($this->get('compress_js',0)){
            ExposeCompressor::compressJS();
        }else{
            //load all scripts from quee
            $this->_loadScripts();
        }
        
        if(isset ($this->jqDom) AND $this->jqDom != NULL){
            $this->_renderCombinedDom();
        }
         //fix the template width and sidebar width
        //$this->_customLayoutWidth();

        //$this->layout->init();
        define('EXPOSE_FINAL', 1);
    }

    //finalized Admin
    public function finalizedAdmin(){
        if($this->isAdmin()){
            $this->_loadStyles();
            $this->_loadScripts();
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
        $templateName = '';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, home, template, params');
        $query->from('#__template_styles');
        $query->where('client_id = 0');
        $query->where('home = 1');
        $db->setQuery($query);
        $templates= $db->loadObjectList('template');
        foreach($templates as $template){
            $templateName = $template->template;
        }

        return $templateName;
    }

    private function loadCoreStyleSheet()
    {
        if($this->platform = 'desktop')
        {
            $files = array('expose.css','joomla.css');
            $this->addLink($files,'css',1);
        }else{
            $browser = strtolower($this->browser->getBrowser());
            $file = 'expose-'.$browser.'.css';
            $this->addLink($file,'css',1);

        }
    }

    public function addLink($file, $type, $priority=10)
    {
        if(is_array($file)){
            foreach($file as $path){
                $this->addLink($path, $type, $priority);
            }
        }

        jimport('joomla.filesystem.file');

        $burl = $this->exposeUrl . '/interface/' . $type . '/';
        $turl = $this->templateUrl  . '/' .$type . '/';

        if($type == 'css')
        {
            $dir = dirname($file);
            //check file source
            if($dir != '.')
            {
                if(preg_match('/^http/', $file))
                {
                    $this->styleSheets[$priority]['url'][] = $file;
                    return;
                }
                //path is included so check its existence and add
                $path = $this->getFilePath($file);
                if(JFile::exists($path)){
                    $this->styleSheets[$priority]['local'][$path] = $file;
                    return;
                }

            }else{
                $tpath = $this->getFilePath($turl.$file);
                $bpath = $this->getFilePath($burl.$file);
                //cross check both base and template path for this file
                if(JFile::exists($tpath))
                {
                    $this->styleSheets[$priority]['local'][$tpath] = $turl.$file;
                    return;
                }elseif(JFile::exists($bpath)){
                    $this->styleSheets[$priority]['local'][$bpath] = $burl.$file;
                    return;
                }
            }

            return;
        }

        if($type == 'js')
        {
            $dir = dirname($file);
            //check file source
            if($dir != '.')
            {
               if(preg_match('/^http/', $file))
               {
                   $this->scripts[$priority]['url'][] = $file;
                   return;
               }
               //path is included so check its existence and add
               $path = $this->getFilePath($file);
               if(JFile::exists($path)){
                   $this->scripts[$priority]['local'][$path] = $file;
                   return;
               }

           }else{
               $tpath = $this->getFilePath($turl.$file);
               $bpath = $this->getFilePath($burl.$file);
               //cross check both base and template path for this file
               if(JFile::exists($tpath))
               {
                   $this->scripts[$priority]['local'][$tpath] = $turl.$file;
                   return;
               }elseif(JFile::exists($bpath)){
                   $this->scripts[$priority]['local'][$bpath] = $burl.$file;
                   return;
               }
           }

           return;
            //$this->scripts[]= $file;
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

    public function getStyleSheet(){
        return $this->styleSheets;
    }
    public function getScripts(){
        return $this->scripts;
    }

    private function _loadPresetStyle(){
        //if(defined('EXPOSE_FINAL')) return;
        $preset_file = (isset ($_COOKIE[$this->templateName.'_style'])) ? $_COOKIE[$this->templateName.'_style'] : $this->get('style','style1');
        if(isset ($_REQUEST['style'])){
            setcookie($this->templateName.'_style',$_REQUEST['style'],time()+3600,'/');
            $preset_file = $_REQUEST['style'];
        }
        $preset_file = $preset_file.'.css';
        $this->addLink($preset_file, 'css');
    }

    public function addInlineStyles($content){
        return $this->document->addStyleDeclaration($content);
    }


    public function addjQDom($js=NULL){
        if($js != NULL){
            $this->jqDom .= "\t\t\t" . $js ."\n";
        }
    }

    private function _renderCombinedDom(){
        $jqNoConflict = "\n\t\t".'jQuery.noConflict();'."\n";
        $dom = '';
        //add noConflict
        $dom .= $jqNoConflict;
        $dom .= "\n\t\t" . 'jQuery(document).ready(function($){'."\n".$this->jqDom."\n\t\t});";

        $this->document->addScriptDeclaration($dom);
    }

    public function addjQuery(){
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
                case 'google_cdn':
                    $file = 'https://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js';
                    break;
                case 'local':
                    $file = 'jquery-'.$version.'.min.js';
                    break;
            }
            $this->app->set('jQuery',$version);
            $this->addLink($file,'js',1);
        }
        return;
    }

    private function _loadStyles(){
        //if(defined('EXPOSE_FINAL')) return;
        $this->_loadPresetStyle();
        $this->loadCoreStyleSheet();
        ksort($this->styleSheets);

        //load all remote file first.
        foreach($this->styleSheets as $key => $type)
        {
            if(isset($type['url']))
            {
                foreach($type['url'] as $url){
                    $this->document->addStyleSheet($url);
                }
            }
        }

        foreach($this->styleSheets as $key => $type){
            if(isset($type['local']))
            {
                foreach($type['local'] as $url)
                {
                    $version = '?v=' . EXPOSE_VERSION;
                    $this->document->addStyleSheet($url.$version);
                }
            }
        }

    }

    private function _loadScripts(){
        //load jquery first
        $this->addjQuery();
        ksort($this->scripts);

        //load all remote file first.
        foreach($this->scripts as $key => $type)
        {
            if(isset($type['url']))
            {
                foreach($type['url'] as $url){
                    $this->document->addScript($url);
                }
            }
        }

        foreach($this->scripts as $key => $type){
            if(isset($type['local']))
            {
                foreach($type['local'] as $url)
                {
                    $version = '?v=' . EXPOSE_VERSION;
                    $this->document->addScript($url.$version);
                }
            }
        }

    }

    private function _setDirection(){
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



    private function _customLayoutWidth(){
        if(defined('EXPOSE_FINAL')) return;
        $css = "/*dynamic css*/ \n";
        if($this->layout->isMobile()){
            $templWidth     = '320px';
            $css .= '.tx-container{width:'.$templWidth.'}';
        }
        else{
            $templWidth = (isset ($_COOKIE[$this->templateName.'_templateWidth'])) ? $_COOKIE[$this->templateName.'_templateWidth'] : $this->get('template_width');
            if(isset ($_REQUEST['templateWidth'])){
                setcookie($this->templateName.'_templateWidth',$_REQUEST['templateWidth'],time()+3600,'/');
                $templWidth = $_REQUEST['templateWidth'];
            }

            if($templWidth == 'fluid'){
              $css      .= '.tx-container{width:95%}';
            }else {
                $width   = $this->get('width','980').'px';
                $css    .= '.tx-container{width:'.$width.'}';
            }
        }
        $this->addInlineStyles($css);
    }


    public function displayHead(){
        if(defined('EXPOSE_FINAL')) return;
        if(!$this->isAdmin()){
            //output joomla head
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">';
            echo '<jdoc:include type="head" />';
        }
    }

    public function generateBodyClass(){
        $class  = NULL;
        $class .= $this->get('style');
        $class .= ' '.$this->direction;
        $class .= ' layout-'. $this->get('layout_type');

        return 'class="'.$class.'"';
    }
    
    public function isHomePage(){
        if (EXPOSE_JVERSION == '15') {
            return (JRequest::getCmd( 'view' ) == 'frontpage') ;
        }
        else{
            global $app;
            $menu = $app->getMenu();
            return ($menu->getDefault()->id === $menu->getActive()->id) ? TRUE : FALSE;
        }
        
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
        if($this->browser->isMobile() AND ($browserName == 'iPhone' OR $browserName == 'Android')){
            $this->platform = 'mobile';
        }else{
            $this->platform = 'desktop';
        }

    }
}