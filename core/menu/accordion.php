<?php
/**
 * @package     Expose
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        dropline.php
 *
 * This modified class is based on T3 GPL Framework.
 **/

// TODO: This is basic skeliton of Accordion menu, accordion feature dosen't implement yet.
defined('_JEXEC') or die('Restricted access');

if (!defined ('_EXPOSE_ACCORDION_MENU')) {
	define ('_EXPOSE_ACCORDION_MENU', 1);
	require_once (dirname(__FILE__).DS."basemenu.php");

	class ExposeAccordionMenu extends ExposeBaseMenu{
       /**
        * Constructor
        *
        * @param array $params  An array parameter
        *
        * @return void
        */
        function __construct($params)
        {
            $params->set('megamenu', 0); // Tell its not a mega menu
            parent::__construct($params);
        }

        /**
        * Echo markup before menu markup
        *
        * @param int $startlevel  Start menu level
        * @param int $endlevel    End menu level
        *
        * @return void
        */
       function beginMenu($startlevel = 0, $endlevel = 10)
       {
           return; 
       }

       /**
        * Echo markup after menu markup
        *
        * @param int $startlevel  Start menu level
        * @param int $endlevel    End menu level
        *
        * @return void
        */
       function endMenu($startlevel = 0, $endlevel = 10)
       {
           return;

       }

       /**
         * Echo markup before menu items markup
         *
         * @param int  $pid     Menu item id
         * @param int  $level   Menu item level
         * @param bool $return  Return or not
         *
         * @return mixed  Markup if return = true, otherwise VOID
         */
        function beginMenuItems($pid = 0, $level = 0)
        {
            return;
        }

        /**
         * Echo markup after menu items markup
         *
         * @param int  $pid     Menu item id
         * @param int  $level   Menu item level
         * @param bool $return  Return or not
         *
         * @return mixed  Markup if return = true, otherwise VOID
         */
        function endMenuItems($pid = 0, $level = 0)
        {
            return; 
        }

        /**
         * Echo markup before submenu items markup
         *
         * @param int    $pid     Menu id
         * @param int    $level   Level
         * @param string $pos     Position
         * @param int    $i       Index
         * @param string $return  Return or not
         *
         * @return mixed  Markup if return = true, otherwise VOID
         */
        function beginSubMenuItems($pid = 0, $level = 1, $pos = null, $i = 0, $return = false)
        {
            $level = (int) $level + 1;
            $data = '';

            if (@$this->children[$pid]) $data .= "<ul class=\"l$level\">";
            echo $data;
        }

        /**
         * Echo markup after submenu items markup
         *
         * @param int    $pid     Menu id
         * @param int    $level   Level
         * @param string $return  Return or not
         *
         * @return mixed  Markup if return = true, otherwise VOID
         */
        function endSubMenuItems($pid = 0, $level = 0, $return = false)
        {
            $data = '';
            if (@$this->children[$pid]) $data .= "</ul>";
            
            echo $data;
        }

        
        /**
         * Generate class item
         *
         * @param object $mitem  Menu item
         * @param int    $level  Menu level
         * @param string $pos    Position
         *
         * @return void
         */
        function genClass($mitem, $level, $pos)
        {
            $cls = '';
            $iParams = new JForm($mitem->params);
            if($level == NULL) $level = 1;

            // Set item id
            $cls .= 'item' . $mitem->id ;

            if ($level < $this->getParam('endlevel')) $cls .= " parent";

            // Set active class
            $active = in_array($mitem->id, $this->open);
            if (!preg_match('/grouped/', $cls)) $cls .= ($active ? ' active' : '');

            // additionl class coming from menu admin
            if ($mitem->megaparams->get('class')) $cls .= " " . $mitem->megaparams->get('class');

            // Set position first/last
            $cls .=  ($pos ? " $pos" : '');

            return $cls;
        }

        /**
         * Echo markup before menu item markup
         *
         * @param object $mitem  Menu item
         * @param int    $level  Menu level
         * @param string $pos    Position
         *
         * @return void
         */
        function beginMenuItem($mitem = null, $level = 0, $pos = '')
        {
            $active = $this->genClass($mitem, $level, $pos);
            if ($active) $active = " class=\"$active\"";
            echo "<li $active>";
            //if ($mitem->megaparams->get('group')) echo "<div class=\"group\">";
        }

        /**
         * Echo markup after menu item markup
         *
         * @param object $mitem  Menu item
         * @param int    $level  Menu level
         * @param string $pos    Position
         *
         * @return void
         */
        function endMenuItem($mitem = null, $level = 0, $pos = '')
        {
            echo "</li>";
        }

        
	}
}
?>
