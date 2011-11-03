<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        splitmenu.php
 *
 * This modified class is based on T3 GPL Framework.
 **/

defined('_JEXEC') or die('Restricted access');

if (!defined ('_EXPOSE_SPLIT_MENU')) {
	define ('_EXPOSE_SPLIT_MENU', 1);
	require_once (dirname(__FILE__) . DS . "basemenu.php");

	class ExposeSplitMenu extends ExposeBaseMenu{

		function __construct (&$params) {
			parent::__construct($params);

			//To show sub menu on a separated place
			$this->showSeparatedSub = true;
		}

		function beginMenu($startlevel=0, $endlevel = 10){
			if ($startlevel == 0) {
				echo "<div id=\"tx-splitmenu\" class=\"mainlevel clearfix\">\n";
			} else {
				echo "<div class=\"sublevel\">\n";
			}
		}
		function endMenu($startlevel=0, $endlevel = 10){
			echo "\n</div>";
		}
		function beginMenuItems($pid=0, $level=0){
			if ($level == 1)
				echo "<ul class=\"active\">";
			else
				echo "<ul>";
		}
		function genMenu($startlevel=0, $endlevel = 10){
			if ($startlevel == 0) parent::genMenu(0,0);
			else parent::genMenu($startlevel, $endlevel);
		}

	}
}
?>
