<?php
/**
 * Expose Compressior for Css and Js.
 * For Css compression we used Css Minifier Engine
 * For Js compression we used JSMIN Engine
 *
 * @package     Expose
 * @version     3.0.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 *
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

class ExposeCompressor {

    public static function combine()
    {

    }

    public static function minifyCss()
    {

    }

    public static function minifyJs()
    {

    }

    public static function processRemoteFiles()
    {

    }

    protected function getFileheader($type, $expireTime)
    {

    }


    public function compressCSS(){
        global $expose;
        $cacheTime = $expose->get('cache_time');
        $output = NULL;
        //precess remote file first
        $files = self::_processRemoteFiles($expose->_styleSheets,'css');
        $md5sum = "";
        
        jimport('joomla.filesystem.file');
        expose_import('libs.CssCompressor');
        
        //group files according to is dir
        foreach($files as $file){
            $file = str_replace($expose->baseUrl, '', $file);
            $filename = basename($file);
            $path = dirname($file);
            if(JFile::exists($file)){
                //$md5sum .= md5($file);
                $groupFiles[$path][$filename] = $file;
            }
        }
        //build cache and load it
        foreach($groupFiles as $path=>$files){
            if(!is_writable($path)){
                foreach($path as $file => $url){
                    $url = $expose->baseUrl . $url;
                    //$expose->addStyleSheet($url);
                }
            }
            else{
                //build file name
                foreach($files as $name=>$url){
                    $md5sum .= md5($url);
                }
                $cacheFileName = 'css-' . md5($md5sum) . '.php';
                $cacheFilePath = $path . '/' . $cacheFileName;

                //check cache file existance
                if (JFile::exists($cacheFilePath)) {
                    $diff = (time() - filectime($cacheFilePath));
                } else {
                    $diff = $cacheTime + 1;
                }
                if($diff > $cacheTime){
                $output = ExposeCompressor::_getOutHeader('css');
                foreach($files as $name => $url){
                    $content = JFile::read($url);
                    $content = CssCompressor::process($content);
                    $output .= "\n\n/*** " . $name . " ***/\n\n" . $content;
                }
                    JFile::write($cacheFilePath, $output);
                }
            $cacheUrl = $cacheFilePath;
            $expose->doc->addStyleSheet($cacheUrl);
            }
        }
    }

    public function compressJS(){
        global $expose;
        $cacheUrl = $expose->baseUrl . 'cache/';
        $cacheTime = $expose->get('cache_time');
        $existFiles = array();
        $output = NULL;
        $jcoreJs = array_keys($expose->doc->_scripts); //cache joomla core js files
        $files = self::_processRemoteFiles($expose->_scripts,'js'); //get only local files
        $files = array_merge_recursive($jcoreJs, $files); //marge joomla core and local file

        //reset core scripts from joomla header
        foreach($files as $path){
            if(array_key_exists($path,$expose->doc->_scripts)){
                //unset it
                unset($this->doc->_scripts[$path]);
            }
        }

        $md5sum = "";
        jimport('joomla.filesystem.file');
        foreach($files as $key=>$path){
            $path = str_replace($expose->baseUrl, '', $path);
            if(JFile::exists($path)){
                $md5sum .= md5($path);
                $existFiles[basename($path)] = $path;
            }
        }
        
        $cacheFileName = 'js-' . md5($md5sum) . '.php';
        $cacheFullPath = JPATH_CACHE . DS . $cacheFileName;
        //check for expose base cache dir. if not exist create it first
        if(!is_writable(JPATH_CACHE)){
            foreach($existFiles as $path){
                $expose->doc->addScript($path);
            }
        }else{
            //check cache file existance
            if (JFile::exists($cacheFullPath)) {
                $diff = (time() - filectime($cacheFullPath));
            } else {
                $diff = $cacheTime + 1;
            }
            //import JSMIN Engine
            expose_import('libs.JSMin');
            if($diff > $cacheTime){
                $output = ExposeCompressor::_getOutHeader('js');
                foreach($existFiles as $file => $path){
                    $content = JFile::read($path);
                    $content = JSMin::minify($content);

                    $output .= "\n\n/*** " . basename($file) . " ***/\n\n" . $content;
                }
                JFile::write($cacheFullPath, $output);
            }
        $cacheUrl = $cacheUrl . $cacheFileName;
        $expose->doc->addScript($cacheUrl);
        }
    }

    private function _processRemoteFiles($files = array(), $type= 'css'){
        global $expose;
        $remotFiles = array();
        $localFiles = array();
        foreach($files as $file => $path){
            if (preg_match('/^http/', $path)) {
                $remotFiles[] = $path;
            }else{
                $localFiles[$file] = $path;
            }
        }

        foreach ($remotFiles as $url){
            if($type == 'css'){
                $expose->doc->addStyleSheet($url);
            }else{
                $expose->doc->addScript($url);
            }
            
        }

        return $localFiles;
    }

    /* This function borrowed from Gantry GPL Framework
     * Author: RocketTheme
     */
    private function _getOutHeader($type = "css", $expires_time = 1440) {
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

