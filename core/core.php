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
    public  $_styleSheets = array();
    public  $_styles = NULL;
    public  $_scripts = array();
    private  $_jqDOM = NULL;

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

    public function isAdmin(){
        return $this->app->isAdmin();
    }

    public function finalizedExpose(){

        //load preset style before loading all stylesset. this will allow to add preset
        //style file at the end of all style files
        $this->_loadPresetStyle();

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
        
        if(isset ($this->_jqDOM) AND $this->_jqDOM != NULL){
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

//        if(!$this->isAdmin()){
//            $app = &JApplication::getInstance('site', array(), 'J');
//            $template = $app->getTemplate();
//            return $template;
//        }
    }

    public function addStyle($file){
        if(defined('EXPOSE_FINAL')) return;
        //TODO: add a override method to override base css file
        $type = 'css';
        $site_url = $this->templateUrl . $type . '/' ;
        $expose_url = $this->exposeUrl . 'interface/'.$type.'/';

        //make sure core stylesheets are loaded before all style
        if(!$this->isAdmin()){
            if(!defined('EXPOSE_CORE_STYLE_LOADED')){
                $core_style_sheet = array('expose.css','joomla.css','menu.css');
//                if($this->browser->isMobile()){
//                    $mob_client = strtolower($this->browser->getBrowser());
//                    $mob_css = 'expose-'. $mob_client.'.css';
//                    $core_style_sheet[]=$mob_css;
//                }
                //load core style
                foreach($core_style_sheet as $styleSheet){
                    if(file_exists($this->templatePath . DS . 'css' . DS . $styleSheet)){
                        $this->_styleSheets[$styleSheet] = $site_url.$styleSheet;
                    }else{
                        $this->_styleSheets[$styleSheet] = $expose_url.$styleSheet;
                    }
                }
                define('EXPOSE_CORE_STYLE_LOADED', 1);
            }
        }

        $dir = dirname($file);
        //check the file source.
        if($dir != '.'){
            //path is included so add it directly
            $this->_styleSheets[$file]= $file;
        }
        else{
            $out_file = $site_url . $file;
            $this->_styleSheets[$file] = $out_file;
        }
    }

    public function addStyles($files=array()){
        if(defined('EXPOSE_FINAL')) return;
        foreach($files as $file){
            $this->addStyle($file);
        }
    }

    private function _loadPresetStyle(){
        //if(defined('EXPOSE_FINAL')) return;
        $preset_file = (isset ($_COOKIE[$this->templateName.'_style'])) ? $_COOKIE[$this->templateName.'_style'] : $this->get('style','style1');
        if(isset ($_REQUEST['style'])){
            setcookie($this->templateName.'_style',$_REQUEST['style'],time()+3600,'/');
            $preset_file = $_REQUEST['style'];
        }
        $preset_file = $preset_file.'.css';
        $this->addStyle($preset_file);
    }

    public function addInlineStyles($content){
        if(defined('EXPOSE_FINAL')) return;
        return $this->document->addStyleDeclaration($content);
    }

    public function addScript($file){
        if(defined('EXPOSE_FINAL')) return;
        //make sure jQuery is loaded before all scripts
        $this->addjQuery();

        $this->_scripts[]= $file;

        //$this->document->addScript($file);
    }

    public function addScripts($files= array()){
        if(defined('EXPOSE_FINAL')) return;
        foreach($files as $file){
            $this->addScript($file);
        }
    }

    public function addjQDom($js=NULL){
        if(defined('EXPOSE_FINAL')) return;
        if($js != NULL){
            $this->_jqDOM .= "\t\t\t" . $js ."\n";
        }
    }
    private function _renderCombinedDom(){
        $jqNoConflict = "\n\t\t".'jQuery.noConflict();'."\n";
        $dom = '';
        //add noConflict
        $dom .= $jqNoConflict;
        $dom .= "\n\t\t" . 'jQuery(document).ready(function($){'."\n".$this->_jqDOM."\n\t\t});";

        $this->document->addScriptDeclaration($dom);
    }

    public function addjQuery(){
        //come form admin? just add jquery without asking any question because jquery is heart of
        //expose admin
        if($this->isAdmin() AND !$this->app->get('jQuery')){
            $file = $this->exposeUrl.'/interface/js/jquery-1.7.1.min.js';
            $this->app->set('jQuery','1.5.1');
            $this->_scripts[]= $file;
            return;
        }
        
        if($this->get('jquery_loader') AND !$this->app->get('jQuery')){
            $version = $this->get('jquery_version');
            //get the cdn
            $cdn = $this->get('jquery_source');
            switch ($cdn){
                case 'google_cdn':
                    $file = 'https://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js';
                    break;
                case 'ms_cdn':
                    $file = 'http://ajax.aspnetcdn.com/ajax/jQuery/jquery-'.$version.'.min.js';
                    break;
                case 'local':
                    $file = $this->exposeUrl.'interface/js/jquery-'.$version.'.min.js';
                    break;
            }
            $this->app->set('jQuery',$version);
            $this->_scripts[]= $file;
        }
        return;
    }

    private function _loadStyles(){
        if(defined('EXPOSE_FINAL')) return;
        //print_r($this->_styleSheets);
        foreach($this->_styleSheets as $file){
            $this->document->addStyleSheet($file);
        }
    }
    private function _loadScripts(){
        foreach($this->_scripts as $script){
            $this->document->addScript($script);
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

    public function loadMenu(){
        $menuType = $this->get('menu_type','mega');
        switch($menuType){
            case 'mega':
                $class = 'MegaMenu';
                $this->hasSubMenu = FALSE;
                //load xpertmenu aka mega menu js file
                $this->addScript($this->exposeUrl.'interface/js/xpertmenu.js');
                $js = "jQuery('#tx-menu').XpertMenu({
                    action:'mouseenter',
                    parent:'.tx-container',
                    hideDelay:'300',
                    transition:\"slide\",
                    easing:\"easeInOutExpo\"
                });";
                $this->addjQDom($js);
                break;
            case 'dropline':
                $class = 'DroplineMenu';
                $this->hasSubMenu = TURE;
                break;
            case 'split':
                $class = 'SplitMenu';
                $this->hasSubMenu = TRUE;
                    
                break;
            default:
                $class = 'MegaMenu';
                $this->hasSubMenu = FALSE;
                break;
        }
        $menu =& loadClass($class,'core/menu',$this->document->params);

        return $menu;
        /*$menu = (isset ($_COOKIE[$this->templateName.'_menu'])) ? $_COOKIE[$this->templateName.'_menu'] : 'superfish';
        if(isset ($_REQUEST['menu'])){
            setcookie($this->templateName.'_menu',$_REQUEST['menu'],time()+3600,'/');
            $menu = $_REQUEST['menu'];
        }*/

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