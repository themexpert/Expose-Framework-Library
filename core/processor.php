<?php
/**
 * Expose Css and Js processor with combine and minify option
 *
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 *
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

jimport('joomla.filesystem.file');

class ExposeProcessor {

    /*
     * Combine function for combine all css and js all together
     *
     * @params string $type either css/js
     *
     * @return void
     *
     * */
    protected static function combine($type)
    {
        global $expose;

        if($type == 'css')
        {
            $source = $expose->styleSheets;

        }elseif($type == 'js'){

            $source = $expose->scripts;

        }
        //sorting key
        ksort($source);

        //Group file located on same path
        $fileGroup = array();
        foreach($source as $key => $v)
        {
            foreach($v as $link){
                if($link->source == 'local')
                {
                    $fileName = basename($link->path);
                    $path = str_replace($fileName, '', $link->path);
                    $fileGroup[$path][$fileName] = str_replace($fileName, '', $link->url);
                }

            }
        }

        self::createFiles($fileGroup, $type);
    }

    /*
     * Create combined file all together based on its relative path and add this to expose css/js record
     *
     * @params array $fileGroup group files by its path
     * @params string $type either css/js
     *
     * @return void
     *
     * */
    protected static function createFiles($fileGroup = array(), $type)
    {
        global $expose;
        $cacheTime = $expose->get('cache-time', 600);
        $cacheFiles = array();
        $url = '';
        $version = '?v=' . EXPOSE_VERSION;

        //if this script is not runing on safe mood then increase the max_execution_time on runtime
        //to avoid interruption
        if( !ini_get('safe_mode') ){
            set_time_limit(120);
        }

        if($type == 'css')
        {
            $minifyCss = $expose->get('minify-css',0);

            foreach ($fileGroup as $path => $files)
            {
                $md5sum = '';
                foreach ($files as $file => $url)
                {
                    $md5sum .= md5($file.$version);
                    $url = $url;
                }

                $cacheFile = 'css-'.$md5sum . '.php';
                $cachePath = $path . DS . $cacheFile;

                //check cache file existence
                if (JFile::exists($cachePath)) {
                    $diff = (time() - filectime($cachePath));
                } else {
                    $diff = $cacheTime + 1;
                }
                if ($diff > $cacheTime)
                {
                    $buffer = self::getFileheader($type);

                    foreach ($files as $file => $url)
                    {
                        $content = JFile::read($path.$file);

                        if($minifyCss)
                        {
                            $content = self::minify($content, $type);
                        }
                        $buffer .= "\n\n/*** " . $url.$file . " ***/\n\n" . $content;

                    }
                   JFile::write($cachePath,$buffer);

                }
                $cacheFiles[$cacheFile] = $url;
            }
            //unset the old stylesheet array and insert newly generated files
            unset($expose->styleSheets);
        }

        if($type == 'js')
        {
            $minifyJs = $expose->get('minify-js',0);

            //create file name
            foreach($fileGroup as $files)
            {
                $md5sum = '';
                foreach ($files as $file => $url)
                {
                   $md5sum .= md5($file);
                }
            }

            $cacheFile = 'js-'.$md5sum . '.php';
            $jspath = JPATH_ROOT . DS . 'cache' . DS . 'expose';
            $jsurl = $expose->baseUrl . '/cache/expose/';
            $cachePath = $jspath . DS . $cacheFile;
            $buffer = '';

            foreach ($fileGroup as $path => $files)
            {
                //check cache file existence
                if (JFile::exists($cachePath)) {
                    $diff = (time() - filectime($cachePath));
                } else {
                    $diff = $cacheTime + 1;
                }
                if ($diff > $cacheTime)
                {
                    foreach ($files as $file => $url)
                    {
                        $content = JFile::read($path.$file);
                        if($minifyJs)
                        {
                            $content = self::minify($content, $type);
                        }
                        $buffer .= "\n\n/*** " . $url.$file . " ***/\n\n" . $content;

                    }
                }
            }

            if ($buffer != NULL)
            {
                $header = self::getFileheader($type);
                $buffer = $header . $buffer;
                JFile::write($cachePath,$buffer);
            }
            unset($expose->scripts);
            $cacheFiles[$cacheFile] = $jsurl;
        }


        foreach($cacheFiles as $file => $url)
        {
            $expose->addLink($url.$file,$type);
        }
    }

    /*
    * Minified the css/js file contents
    *
    * @params string $content css/js file content
    * @params string $type either css/js
    *
    * @return minified content
    *
    * */
    protected static function minify($content, $type)
    {
        if($type == 'css')
        {
            expose_import('libs.csscompressor');
            $content = ExposeCssCompressor::process($content);
            return $content;
        }
        if($type == 'js')
        {
            expose_import('libs.jsmin');
            $content = ExposeJSMin::minify($content);
            return $content;
        }
    }

    /*
    * Main processor function for css/js. It pre-process all css/js dependencies and finally add it to joomla
    * document header.
    *
    * @params string $type either css/js
    *
    * @return void
    *
    * */
    public static function process($type)
    {
        global $expose;

        //if mootools loading is disable and its not edit page.
        if($type == 'js' AND $expose->get('disable-mootools') AND !$expose->isEditpage())
        {
            self::removeMootools();
        }
        //marge all stylesheet and script called by joomla and 3pd extension with Expose
        self::margeStyleScript($type);

        if($type == 'css')
        {
            //load expoe core stylesheets
            $expose->loadCoreStyleSheets();
        }elseif($type == 'js'){
            //always check jquery and add if not exist
            $expose->addjQuery();
        }
        //prevent js/css being loaded
        if($expose->get('prevent-css-js') != NULL)
        {
            $preventUrls = explode(',',$expose->get('prevent-css-js'));
        }

        if($type == 'css')
        {
            if($expose->get('combined-css',0))
            {
                //process remote file first
                self::processRemoteFiles($type);
                self::combine($type);
            }

            ksort($expose->styleSheets);

            foreach($expose->styleSheets as $key => $v){
                foreach($v as $link)
                {
                    if(isset($preventUrls))
                    {
                        if(in_array($link->url, $preventUrls)){
                            continue;
                        }
                    }
                    
                    $expose->document->addStyleSheet($link->url,'text/css',$link->media);                    
                }
            }
        }

        if($type == 'js')
        {
            if($expose->get('combined-js',0))
            {
                //process remote file first
                self::processRemoteFiles($type);

                self::combine('js');

            }
            //sort scripts
            ksort($expose->scripts);

            foreach($expose->scripts as $key => $v){
                foreach($v as $link)
                {
                    if(isset($preventUrls))
                    {
                        if(in_array($link->url, $preventUrls)){
                           continue;
                        }
                    }
                    
                    $expose->document->addScript($link->url);
                }
            }
        }

    }

   /*
    * marge all css/js files from joomla document header with expose record
    *
    * @params string $type either css/js
    *
    * @return void
    *
    * */

    protected static function margeStyleScript($type)
    {
        global $expose;

        if($type == 'css'){
            $joomlaFiles = array_keys($expose->document->_styleSheets);

            foreach($joomlaFiles as $url){
                $expose->addLink($url,'css',1);
                //remove this file from joomla header
                unset($expose->document->_styleSheets[$url]);
            }

        }elseif($type == 'js')
        {
            $i = 1;
            $joomlaFiles = array_keys($expose->document->_scripts);

            foreach($joomlaFiles as $url){
                //add jquery right after mootools files, joomla has 4/5 core mootools file and we just count 4 now
                if($i > 4)
                {
                    $expose->addLink($url,'js',2);
                }else{
                    $expose->addLink($url,'js',0);
                }
                //remove this file from joomla header
                unset($expose->document->_scripts[$url]);
                $i++;
            }
        }
    }

   /*
    * Remove mootools and all its dependencies files.
    *
    * @return void
    *
    * */
    protected static function removeMootools()
    {
        global $expose;

        //iphone/android menu based on mootools so here is the exception
        if($expose->platform == 'mobile') return;

        $coreFiles = array(
            'mootools-core.js',
            'core.js',
            'mootools-more.js',
            'modal.js',
            'caption.js'
        );
        $path = $expose->baseUrl . '/media/system/js/';
        //start unseting
        foreach($coreFiles as $file)
        {
            $key = $path.$file;
            unset($expose->document->_scripts[$key]);
        }
    }

   /*
    * Process all remote file, it used when combined method is called.
    *
    * @params string $type either css/js
    * @return void
    *
    * */
    protected static function processRemoteFiles($type)
    {
        global $expose;

        if($type == 'css')
        {
            $source = $expose->styleSheets;

        }elseif($type == 'js'){
            $source = $expose->scripts;
        }

        foreach($source as $key => $v)
        {
            foreach($v as $link)
            {
                if($link->source == 'url')
                {
                    if($type == 'css')
                    {
                        $expose->document->addStyleSheet($link->url);
                    }
                    if($type == 'js')
                    {
                        $expose->document->addScript($link->url);
                    }
                }
            }
        }

    }

    /*
     * Add expire header in file
     * This function borrowed from Gantry GPL Framework
     * Author: RocketTheme
     */
    protected static function getFileheader($type = "css", $expires_time = 1440)
    {
        if ($type == "css") {
            $header = '<?php
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$expires_time = ' . $expires_time . ';
$offset = 60 * $expires_time ;
$ExpStr = "Expires: " .
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
                ?>';
        } else {
            $header = '<?php
ob_start ("ob_gzhandler");
header("Content-type: application/x-javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$expires_time = ' . $expires_time . ';
$offset = 60 * $expires_time ;
$ExpStr = "Expires: " .
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
                ?>';
        }
        return $header;
    }
}

