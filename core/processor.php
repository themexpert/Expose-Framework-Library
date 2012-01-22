<?php
/**
 * Expose Css and Js processor with combine and minify option
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

jimport('joomla.filesystem.file');

class ExposeProcessor {

    protected static function combine($type)
    {
        global $expose;

        if($type == 'css')
        {
            $source = $expose->styleSheets;

        }elseif($type == 'js'){

            $joomlaFiles = array_keys($expose->document->_scripts);

            foreach($joomlaFiles as $url){
                $expose->addLink($url,'js',1);
                //remove this file from joomla header
                unset($expose->document->_scripts[$url]);
            }
            $source = $expose->scripts;

        }
        //sorting key
        ksort($source);

        //Group file located on same path
        $fileGroup = array();
        foreach($source as $key => $place)
        {
            if(isset($place['local']))
            {
                foreach($place['local'] as $link)
                {
                    $fileName = basename($link->path);
                    $path = str_replace($fileName, '', $link->path);
                    $fileGroup[$path][$fileName] = str_replace($fileName, '', $link->url);
                }
            }
        }

        self::createFiles($fileGroup, $type);
    }

    protected static function createFiles($fileGroup = array(), $type)
    {
        global $expose;
        $cacheTime = $expose->get('cache-time', 600);
        $cacheFiles = array();
        $url = '';
        $version = '?v=' . EXPOSE_VERSION;

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

    public static function process($type)
    {
        global $expose;

        if($type == 'css')
        {
            self::processRemoteFiles($type);

            $expose->loadCoreStyleSheets();

            if($expose->get('combined-css',0))
            {
                self::combine($type);
            }

            ksort($expose->styleSheets);
            $version = '?v=' . EXPOSE_VERSION;

            foreach($expose->styleSheets as $key => $place){
                if(isset($place['local']))
                {
                    foreach($place['local'] as $link)
                    {
                        $expose->document->addStyleSheet($link->url.$version,'text/css',$link->media);
                    }
                }
            }
        }

        if($type == 'js')
        {
            //load jquery first
            $expose->addjQuery();

            self::processRemoteFiles($type);

            if($expose->get('combined-js',0))
            {
                self::combine('js');

            }

            ksort($expose->scripts);
            $version = '?v=' . EXPOSE_VERSION;

            foreach($expose->scripts as $key => $place){
                if(isset($place['local']))
                {
                    foreach($place['local'] as $link)
                    {
                        $expose->document->addScript($link->url.$version);
                    }
                }
            }
        }

    }

    protected static function processRemoteFiles($type)
    {
        global $expose;

        if($type == 'css')
        {
            $source = $expose->styleSheets;

        }elseif($type == 'js'){
            $source = $expose->scripts;
        }

        foreach($source as $key => $place)
        {
           if(isset($place['url']))
           {
               foreach($place['url'] as $link){

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

    /* This function borrowed from Gantry GPL Framework
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

