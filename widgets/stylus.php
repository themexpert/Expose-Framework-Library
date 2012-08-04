<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        stylus.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

/**
 *
 * Custom styling widget.
 * Specially designed for applying custom style on frontend
 *
 **/

class ExposeWidgetStylus extends ExposeWidget{

    public $name = 'stylus';

    public function isInMobile()
    {
        return TRUE;
    }

    public function init()
    {
        global $expose;

        $css = '';
        $prefix = $expose->getPrefix();

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

        $expose->addInlineStyles($css);

    }

    public function setBackgroundImage($param, $dir='backgrounds')
    {
        global $expose;

        $image = $this->get($param);
        $repeat = $this->get($param.'-repeat','repeat');
        $css = '';

        if($image == -1) return;

        $path  = $expose->templateUrl . '/images/'. $dir .'/' . $image;
        $css  .= "background-image: url({$path});";
        $css  .= "background-repeat: $repeat;";

        return $css;
    }
}

?>

