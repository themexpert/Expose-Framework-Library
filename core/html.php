<?php
/**
 * @package     Expose
 * @version     ##VERSION##
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        html.php
 **/

abstract class ExposeHtml
{

    public static function widget($name, $content)
    {
        $html = '';

        $html .= "<div class=\"block widget $name no-title clearfix \">";
            $html .= "<div class=\"content\">";
                $html .= $content;
            $html .= "</div>";
        $html .= "</div>";


        return $html;

    }

    public static function standard($name)
    {

        $html = '<jdoc:include type="modules" name="'.$name.'" style="standard" />';

        return $html;
    }

    public static function tabs($name)
    {
        global $expose;
        $html = '';

        if( !defined('EXPOSE_TRANSITION'))
        {
            $expose->addLink('bs-transition.min.js','js');
            define('EXPOSE_TRANSITION',1);
        }

        if( !defined('EXPOSE_TAB'))
        {
            $expose->addLink('tabs.js','js');
            define('EXPOSE_TAB',1);
        }

        $html .= '<div class="module-tabs tabbable">';
            $html .= '<jdoc:include type="modules" name="'.$name.'" style="tabs" />';
        $html .= '</div>';

        return $html;

    }

    public static function accordion($name)
    {
        global $expose;
        $html = '';

        if( !defined('EXPOSE_TRANSITION'))
        {
            $expose->addLink('bs-transition.min.js','js');
            define('EXPOSE_TRANSITION',1);
        }

        if( !defined('EXPOSE_ACCORDION'))
        {
            $expose->addLink('bs-collapse.min.js','js');
            define('EXPOSE_ACCORDION',1);
        }
        $html .= "<div class=\"accordion\" id=\"acc-$name\">";
            $html .= '<jdoc:include type="modules" name="'.$name.'" style="accordion" positionName="'.$name.'" />';
        $html .= "</div>";

        return $html;
    }

    public static function basic($name)
    {
        $html = '<jdoc:include type="modules" name="'.$name.'" style="basic" />';

        return $html;
    }



}
