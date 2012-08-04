<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        dropline.php
 *
 * This modified class is based on T3 GPL Framework.
 **/

defined('_JEXEC') or die('Restricted access');

if (!defined ('_EXPOSE_DROPLINE_MENU')) {
	define ('_EXPOSE_DROPLINE_MENU', 1);
	require_once (dirname(__FILE__).DS."basemenu.php");

	class ExposeDroplineMenu extends ExposeBaseMenu{
       /**
        * Constructor
        *
        * @param array $params  An array parameter
        *
        * @return void
        */
        function __construct($params)
        {
           parent::__construct($params);

           //To show sub menu on a separated place
           $this->showSeparatedSub = true;
        }

       /**
        * Generate menu
        *
        * @param int $startlevel  Start menu level
        * @param int $endlevel    End menu level
        *
        * @return void
        */
       function genMenu($startlevel = 0, $endlevel = 10)
       {
           if ($startlevel == 0)
               parent::genMenu(0, 0);
           else {
               $this->setParam('startlevel', $startlevel);
               $this->setParam('endlevel', $endlevel);
               $this->beginMenu($startlevel, $endlevel);
               //Sub level
               $pid = $this->getParentId($startlevel - 1);
               if (@$this->children[$pid]) {
                   foreach ($this->children[$pid] as $row) {
                       if (@$this->children[$row->id]) {
                           $this->genMenuItems($row->id, $startlevel);
                       } else {
                           echo "<ul id=\"exdl-subnav{$row->id}\" class=\"clearfix\"><li class=\"empty\">&nbsp;</li></ul>";
                       }
                   }
               }
               $this->endMenu($startlevel, $endlevel);
           }
       }

       /**
        * Generate menu items
        *
        * @param int $pid    Menu item id
        * @param int $level  Level
        *
        * @return void
        * @deprecated
        */
       function genMenuItems1($pid, $level)
       {
           if (@$this->children[$pid]) {
               $this->beginMenuItems($pid, $level);
               $i = 0;
               foreach ($this->children[$pid] as $row) {
                   $pos = ($i == 0) ? 'first' : (($i == count($this->children[$pid]) - 1) ? 'last' : '');

                   $this->beginMenuItem($row, $level, $pos);
                   $this->genMenuItem($row, $level, $pos);
                   // show menu with menu expanded - submenus visible
                   if ($level < $this->getParam('endlevel')) $this->genMenuItems($row->id, $level + 1);
                   $i++;

                   if ($level == 0 && $pos == 'last' && in_array($row->id, $this->open)) {
                       global $jaMainmenuLastItemActive;
                       $jaMainmenuLastItemActive = true;
                   }
                   $this->endMenuItem($row, $level, $pos);
               }
               $this->endMenuItems($pid, $level);
           } else if ($level == 1) {
               echo "<ul id=\"exdl-subnav$pid\" class=\"clearfix\"><li>&nbsp;</li></ul>";
           }
       }

       /**
        * Echo markup before menu items markup
        *
        * @param int $pid    Menu item id
        * @param int $level  Level
        *
        * @return void
        */
       function beginMenuItems($pid = 0, $level = 0)
       {
           if (!$level)
               echo "<ul>";
           else
               echo "<ul id=\"exdl-subnav$pid\" class=\"clearfix\">";
       }

       /**
        * Echo markup before menu item markup
        *
        * @param object $mitem  Menu item id
        * @param int    $level  Level
        * @param int    $pos    Position
        *
        * @return void
        */
       function beginMenuItem($mitem = null, $level = 0, $pos = '')
       {
           $active = $this->genClass($mitem, $level, $pos);

           if($mitem->megaparams->get('desc') != "&nbsp;") $cls = "has-desc";

           if ($active) $active = " class=\"$active $cls clearfix\"";
           else $active = " class=\"$cls\"";

           if (!$level)
               echo "<li id=\"exdl-mainnav{$mitem->id}\"$active>";
           else
               echo "<li id=\"exdl-subnavitem{$mitem->id}\"$active>";
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
           if (!$startlevel)
               echo "<div id=\"exdl-mainnav\">";
           else
               echo "<div id=\"exdl-subnav\">";
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
           echo "</div>";

       }

       /**
        * Check having submenu item
        *
        * @param int $level  Level
        *
        * @return bool  TRUE
        */
       function hasSubMenu($level)
       {
           return true;
       }
	}
}
?>
