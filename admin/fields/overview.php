<?php
/**
 * @package     Expose
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldOverview extends JFormField{

    protected  $type = 'Overview';

    protected function getInput(){

        //make expose object global
        global $expose;

        // Initialize some field attributes.
        $action     = $this->element['action'];
        $class		= (string) $this->element['class'];
        $uri = JURI::getInstance();
        $id = $uri->getVar('id');

        $html = '';

        //overview override path
        $templatePath   = JPATH_ROOT . '/templates/'. getTemplate($id);
        $templateUrl    = JURI::root(true) . '/templates/'. getTemplate($id);

        $imagePath = $templateUrl . '/template_thumbnail.png';
        $templateXml = $templatePath . '/templateDetails.xml';

        $version = '1.0';

        if ( JFile::exists($templateXml) )
        {
            $xml = simplexml_load_file($templateXml);
            $version = $xml->version[0];
        }

        if( JFile::exists( $templatePath . '/overview.php' ) )
        {
            include_once ( $templatePath . '/overview.php' ) ;

        }else{

            $html .= "<div class='overview-panel-left'>";
            $html .= "<div class='overview-inner gradient3 clearfix'>";
                $html .= "<img src='$imagePath' width='275px' height='250px' alt='".$expose->templateName."_preview' />";
                $html .= JText::_('EXPOSE_DESCRIPTION');
            $html .= "</div>";
        $html .= "</div>";

        $html .= "<div class='overview-panel-right'>";
            $html .= "<div class='overview-inner'>";
                $html .= "<div class='template-info gradient clearfix'>";
                    $html .="<div class='template-name'></div>";
                    $html .= "<div class='details'>".JText::_('EXPOSE_TEMPLATE_STYLE_DESC')."</div>";
                $html .= "</div>";

                $html .= "<div class='live-update gradient'>";
                    $html .= "<p class='version-info'>" . JText::_('EXPOSE_TEMPLATE_VERSION') . "<span>" . $version . "</span></p>";
                    $html .= "<p class='version-info'>" . JText::_('EXPOSE_FRAMEWORK_VERSION') . "<span>"  . EXPOSE_VERSION . "</span> </p>";
                    $html .= "<p class='version-guide'>". JText::_('EXPOSE_VERSION_GUIDE') ."</p>";
                $html .= "</div>";

                $html .= "<div class='getting-help gradient3'>";
                    $html .= "<h2 class='help-title'>". JText::_('EXPOSE_GETTING_HELP_TITLE') . "</h2>";
                    $html .=  JText::_('EXPOSE_GETTING_HELP_DESC');
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";
        }

        return $html;
    }
}


