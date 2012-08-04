<?php
/**
 * @package     Expose
 * @version     3.0.1
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
        $html = '';

        $html .= "<div class='overview-panel-left'>";
            $html .= "<div class='overview-inner gradient3 clearfix'>";
                $html .= "<img src='". $expose->baseUrl. '/templates/' . $this->getCurrentTemplate()."/template_thumbnail.png' width='275px' height='250px' alt='".$expose->templateName."_preview' />";
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
                    $html .= "<h2 class='version-title'>". JText::_('Expose v') . EXPOSE_VERSION ."</h2>";
                    $html .= "<p class='version-guide'>". JText::_('EXPOSE_VERSION_GUIDE') ."</p>";
                $html .= "</div>";

                $html .= "<div class='getting-help gradient3'>";
                    $html .= "<h2 class='help-title'>". JText::_('EXPOSE_GETTING_HELP_TITLE') . "</h2>";
                    $html .=  JText::_('EXPOSE_GETTING_HELP_DESC');
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";




        return $html;
    }
    private function getCurrentTemplate()
    {
       //get template name from template id
       $id = JRequest::getInt('id');

       $db = JFactory::getDbo();
       $query = $db->getQuery(true);
       $query->select('template');
       $query->from('#__template_styles');
       $query->where("id=$id");
       $db->setQuery($query);
       $result = $db->loadObject();

       return $result->template;
    }
}


