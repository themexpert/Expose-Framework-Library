<?php
/**
 * @package     Expose
 * @version     3.0.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetLogo extends ExposeWidget{

    public $name = 'logo';

    public function render()
    {
        global $expose;

        $menu = JSite::getMenu();

        // Checking to see if new logo was uploaded. If so then use that. If not then use logo.png within the template image folder.
        if ( $this->get('image') ) {
            $imagePath = $this->get('image', '');
        } else {
            $imagePath = "templates/{$expose->templateName}/images/logo.png";
        }

        // Automatically detect image size of uploaded image
        $imageSize = getimagesize($imagePath);
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        // For good SEO your logo should be an H1 tag on homepage, then degrade to a P tag on inner pages.
        if ( $menu->getActive() == $menu->getDefault() ) {
            $tag = 'h1';
        } else {
            $tag = 'p';
        }

        // If it's a mobile device then set the CSS style to be 100% of the mobile window and resize the logo background image to be 100% wide and automatically set height to stay in perspective.
        if ( $expose->platform == 'mobile' )
        {
            $tagStyle = 'width:100%;';
            $linkStyle = "background: url({$imagePath}) no-repeat; width:{$imageWidth}px; width: 100%; max-height:{$imageHeight}px; -o-background-size: 100% auto; -webkit-background-size: 100% auto; background-size: 100% auto;";
        } else {
            $tagStyle = '';
            $linkStyle = "background: url({$imagePath}) no-repeat; width: {$imageWidth}px; height:{$imageHeight}px;";
        }

        // If there is no text filled in for Logo Text field then default to site title
        if ( $this->get('text') == '' ) {
            $logoText = $expose->app->getCfg('sitename');
        } else {
            $logoText = strip_tags($this->get('text'));
        }

        // Pull in tagline if it's set. If it's not suppose to be visible then hide the tagline by positioning it off of the page.
        if ( ( $this->get('display-tagline') ) AND ( $this->get('tagline') ) ) {
            $tagline = "<span class='logo-tagline'>{$this->get('tagline', '')}</span>";
        } else {
            $tagline = "<span style='position:absolute; top:-999em;'> - {$this->get('tagline', '')} </span>";
        }

        // Output the logo. Determine whether it's text or an image, then pull in all the values set previously to display properly.
        if ( $this->get('type') == 'text' )
        {
            $logo = "<{$tag} id='ex-logo' class='brand {$this->get('type')}'> <a href='{$expose->baseUrl}'>$logoText $tagline</a>  </{$tag}>";

        } else {

            $logo = "<{$tag} id='ex-logo' class='brand {$this->get('type')}' style='{$tagStyle}'> <a style='{$linkStyle} display:block;text-indent: -9999px'  href='{$expose->baseUrl}'>$logoText $tagline</a>  </{$tag}>";

        }

        return $logo;
    }
}
