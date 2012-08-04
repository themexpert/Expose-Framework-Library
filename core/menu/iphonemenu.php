<?php
    /**
     * @package     Expose
     * @version     3.0.1
     * @author      ThemeXpert http://www.themexpert.com
     * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
     * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
     * @file        splitmenu.php
     *
     * This modified class is based on T3 GPL Framework.
     **/

defined('_JEXEC') or die('Restricted access');

if (!defined ('_EXPOSE_IPHONE_MENU')) {
    define ('_EXPOSE_IPHONE_MENU', 1);
    require_once (dirname(__FILE__) . DS . "basemenu.php");

    class ExposeIphoneMenu extends ExposeBaseMenu{

        /**
         * Constructor
         *
         * @param array &$params  An array parameter
         */
        function __construct(&$params)
        {
            parent::__construct($params);
            //To show sub menu on a separated place
            $this->showSeparatedSub = true;
        }

        /**
         * Echo markup before a menu markup
         *
         * @param int $startlevel  Start menu level
         * @param int $endlevel    End menu level
         *
         * @return void
         */
        function beginMenu($startlevel = 0, $endlevel = 10)
        {
        }

        /**
         * Echo markup after a menu markup
         *
         * @param int $startlevel  Start menu level
         * @param int $endlevel    End menu level
         *
         * @return void
         */
        function endMenu($startlevel = 0, $endlevel = 10)
        {
        }

        /**
         * Echo markup before menu items markup
         *
         * @param int $pid    Menu item id
         * @param int $level  Menu item level
         *
         * @return void
         */
        function beginMenuItems($pid = 0, $level = 0)
        {
            if ($pid && isset($this->items[$pid])) {
                echo "<ul id=\"nav$pid\" title=\"{$this->items[$pid]->name}\" class=\"toolbox\">";
            } else {
                echo "<ul id=\"ex-iphonemenu\" title=\"Menu\" class=\"toolbox\">";
            }
        }

        /**
         * Generate menu item
         *
         * @param object $item   Menu item
         * @param int    $level  Level of menu item
         * @param string $pos    Position of menu item
         * @param int    $ret    Return or show data
         *
         * @return mixed  void if ret = 1, otherwise string data of  menu item generating
         */
        function genMenuItem($item, $level = 0, $pos = '', $ret = 0)
        {
            $data = parent::genMenuItem($item, $level, $pos, true);
            if (@$this->children[$item->id]) {
                $tmp = $item;
                $data .= "<a class=\"ex-folder\" href=\"#nav{$tmp->id}\" title=\"{$tmp->name}\">&gt;</a>";
            }
            if ($ret)
                return $data;
            else
                echo $data;
        }

        /**
         * Generate menu items
         *
         * @param int $pid    Menu item
         * @param int $level  Menu level
         *
         * @return void
         */
        function genMenuItems($pid, $level)
        {
            if (@$this->children[$pid]) {
                $this->beginMenuItems($pid, $level);
                $i = 0;
                foreach ($this->children[$pid] as $row) {
                    $pos = ($i == 0) ? 'first' : (($i == count($this->children[$pid]) - 1) ? 'last' : '');

                    $this->beginMenuItem($row, $level, $pos);
                    $this->genMenuItem($row, $level, $pos);
                    // show menu with menu expanded - submenus visible
                    $i++;

                    $this->endMenuItem($row, $level, $pos);
                }
                $this->endMenuItems($pid, $level);

                foreach ($this->children[$pid] as $row) {
                    if ($level < $this->getParam('endlevel')) $this->genMenuItems($row->id, $level + 1);
                }
            }
        }
    }
}