<?php
/**
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

//import parent gist class
expose_import('core.widget');

/**
 * Webfonts class render Google web font in frontend
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

        $rules = NULL;
        $render = FALSE;

        //body font settings
        if($this->get('body-font') != '0' AND $this->get('body-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($this->get('body-font'))}';";
            $this->css .= $this->get('body-selectors'). '{'. $family .'}';
            $this->urls[]= $this->gfontUrl . $this->get('body-font') . $this->subset;

            $render = TRUE;
        }

        //Main navigation font settings
        if($this->get('menu-font') != '0' AND $this->get('menu-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($this->get('menu-font'))}';";
            $this->css .= $this->get('menu-selectors'). '{'.$family .'}';
            $this->urls[]= $this->gfontUrl . $this->get('menu-font') . $this->subset;

            $render = TRUE;
        }

        //heading font settings
        if($this->get('heading-font') != '0' AND $this->get('heading-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($this->get('heading-font'))}';";
            $this->css .= $this->get('heading-selectors'). '{'.$family.'}';
            $this->urls[]= $this->gfontUrl . $this->get('heading-font') . $this->subset;

            $render = TRUE;
        }
        //module header font settings
        if($this->get('module-font') != '0' AND $this->get('module-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($this->get('module-font'))}';";
            $this->css .= $this->get('module-selectors'). '{'.$family.'}';
            $this->urls[]= $this->gfontUrl . $this->get('module-font') . $this->subset;

            $render = TRUE;
        }

        if($render) $this->renderFonts();

    }

    protected function cleanName($name)
    {
        $pos = strpos($name,':');
        if($pos === FALSE){
            $name = str_replace('+',' ', $name);
        }
        else{
            $name = substr( $name,0, strpos($name,':') );
            $name = str_replace('+',' ',$name);
        }
        return $name;
    }

    public function renderFonts()
    {
        global $expose;

        foreach($this->urls as $url){
            $expose->document->addStyleSheet($url);
        }
        $expose->document->addStyleDeclaration($this->css);
    }

}

