<?php
/**
 * This typography class only support google web fonts API.
 *
 * @package     Expose
 * @version     2.0   
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        typography.php
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
        if($expose->get('body_font') != 'none' AND $expose->get('body_selectors') != NULL){
            if($expose->get('body_custom_css') == '1'){
                $rules = $expose->get('body_css_rules');
            }
            $family= "font-family:'{$this->cleanName($expose->get('body_font'))}';";
            $this->css .= $expose->get('body_selectors'). '{'.$family. $rules .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('body_font');
        }
        //Main navigation font settings
        if($expose->get('menu_font') != 'none' AND $expose->get('menu_selectors') != NULL){
            if($expose->get('menu_custom_css') == '1'){
                $rules = $expose->get('menu_css_rules');
            }
            $family= "font-family:'{$this->cleanName($expose->get('menu_font'))}';";
            $this->css .= $expose->get('menu_selectors'). '{'.$family. $rules .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('menu_font');
        }
        //heading font settings
        if($expose->get('heading_font') != 'none' AND $expose->get('heading_selectors') != NULL){
            if($expose->get('heading_custom_css') == '1'){
                $rules = $expose->get('heading_css_rules');
            }
            $family= "font-family:'{$this->cleanName($expose->get('heading_font'))}';";
            $this->css .= $expose->get('heading_selectors'). '{'.$family. $rules .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('heading_font');
        }
        //module header font settings
        if($expose->get('module_font') != 'none' AND $expose->get('module_selectors') != NULL){
            if($expose->get('module_custom_css') == '1'){
                $rules = $expose->get('module_css_rules');
            }
            $family= "font-family:'{$this->cleanName($expose->get('module_font'))}';";
            $this->css .= $expose->get('module_selectors'). '{'.$family. $rules .'}';
            $this->urls[]= $this->gfontUrl . $expose->get('module_font');
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
            $expose->doc->addStyleSheet($url);
        }
        $expose->doc->addStyleDeclaration($this->css);
    }

}

