<?php
/**
 *
 * @package     Expose
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 *
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

/**
 * Webfonts class render Google web font and general font family
 **/

class ExposeWidgetWebfonts extends ExposeWidget{

    public $name = 'webfonts';

    protected $css = NULL;
    protected $urls = array();
    protected $gfonts = array();
    protected $gfontUrl = 'http://fonts.googleapis.com/css?family=';
    protected $subset = '&subset=latin,latin-ext';

    public function isEnabled()
    {
        return TRUE;
    }

    public function isInMobile()
    {
        return TRUE;
    }

    public function init(){

        global $expose;
        //$rules = NULL;
        $renderUrls = FALSE;

        //body font settings
        if($this->get('body-font') != '0' AND $this->get('body-selectors') != NULL)
        {
            $type = $this->getType( $this->get('body-font') );
            $name = $this->cleanName( $this->get('body-font') );
            $family= "font-family: $name ;";

            $this->css .= $this->get('body-selectors'). '{'. $family .'}';

            if($type == 'gfont')
            {
                $this->urls[]= $this->gfontUrl . $this->get('body-font') . $this->subset;
                $renderUrls = TRUE;
            }
        }

        //Main navigation font settings
        if($this->get('menu-font') != '0' AND $this->get('menu-selectors') != NULL)
        {
            $type = $this->getType( $this->get('menu-font') );
            $name = $this->cleanName( $this->get('menu-font') );
            $family= "font-family: $name;";

            $this->css .= $this->get('menu-selectors'). '{'.$family .'}';

            if($type == 'gfont')
            {
                $this->urls[]= $this->gfontUrl . $this->get('menu-font') . $this->subset;
                $renderUrls = TRUE;
            }
        }

        //heading font settings
        if($this->get('heading-font') != '0' AND $this->get('heading-selectors') != NULL)
        {
            $type = $this->getType( $this->get('heading-font') );
            $name = $this->cleanName( $this->get('heading-font') );
            $family= "font-family: $name;";

            $this->css .= $this->get('heading-selectors'). '{'.$family.'}';
            if($type == 'gfont')
            {
                $this->urls[]= $this->gfontUrl . $this->get('heading-font') . $this->subset;
                $renderUrls = TRUE;
            }
        }

        //module header font settings
        if($this->get('module-font') != '0' AND $this->get('module-selectors') != NULL)
        {
            $type = $this->getType( $this->get('module-font') );
            $name = $this->cleanName( $this->get('module-font') );
            $family= "font-family:$name;";

            $this->css .= $this->get('module-selectors'). '{'.$family.'}';
            if($type == 'gfont')
            {
                $this->urls[]= $this->gfontUrl . $this->get('module-font') . $this->subset;
                $renderUrls = TRUE;
            }
        }

        if($renderUrls) $this->renderUrls();

        $expose->document->addStyleDeclaration($this->css);

    }

    protected function cleanName($name)
    {
        $pos = strpos($name,':');

        if( $this->getType($name) == 'gfont')
        {
            if($pos === FALSE){
                $name = str_replace('+',' ', $name);
                $name = "'$name'";
            }
            else{
                $name = substr( $name, 0, strpos($name,':') );
                $name = str_replace('+',' ',$name);
                $name = "'$name'";
            }
        }

        return $name;
    }

    public function renderUrls()
    {
        global $expose;

        if( count($this->urls) > 0){
            foreach($this->urls as $url){
                $expose->document->addStyleSheet($url);
            }
        }
    }

    protected function getType($name)
    {
        //default type is google font
        $type = 'gfont';

        $pos = strpos($name, ',');

        if ( $pos !== FALSE)
        {
            $type = 'general';
        }
        $pos = strpos($name, '-webfont');

        if ( $pos !== FALSE)
        {
            $type = 'webfont';
        }

        return $type;
    }

}

