<?php
/**
 * This typography class only support google web fonts API.
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

class ExposeTypography {

    protected $css = NULL;
    protected $urls = array();
    protected $gfonts = array();
    protected $gfontUrl = 'http://fonts.googleapis.com/css?family=';



    public function __construct(){
        global $expose;
        $rules = NULL;
        //body font settings
        if($expose->get('body-font') != 0 AND $expose->get('body-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($expose->get('body-font'))}' !important;";
            $this->css .= $expose->get('body-selectors'). '{'. $family .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('body-font');
        }
        //Main navigation font settings
        if($expose->get('menu-font') != 0 AND $expose->get('menu-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($expose->get('menu-font'))}' !important;";
            $this->css .= $expose->get('menu-selectors'). '{'.$family .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('menu-font');
        }
        //heading font settings
        if($expose->get('heading-font') != 0 AND $expose->get('heading-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($expose->get('heading-font'))}' !important;";
            $this->css .= $expose->get('heading-selectors'). '{'.$family.'}';
            $this->urls[]= $this->gfontUrl . $expose->get('heading-font');
        }
        //module header font settings
        if($expose->get('module-font') != 0 AND $expose->get('module-selectors') != NULL){

            $family= "font-family:'{$this->cleanName($expose->get('module-font'))}' !important;";
            $this->css .= $expose->get('module-selectors'). '{'.$family.'}';
            $this->urls[]= $this->gfontUrl . $expose->get('module-font');
        }
    }
    protected function cleanName($name){
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
    public function renderFonts(){
        global $expose;

        foreach($this->urls as $url){
            $expose->document->addStyleSheet($url);
        }
        $expose->document->addStyleDeclaration($this->css);
    }

}

