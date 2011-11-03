<?php
/**
 * @package     Expose
 * @version     2.0
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
		function __construct ($params) {
			parent::__construct($params);

			//To show sub menu on a separated place
			$this->showSeparatedSub = true;
		}

	    function genMenu($startlevel=0, $endlevel = 10){
			if ($startlevel == 0) parent::genMenu(0,0);
			else {
				$this->setParam('startlevel', $startlevel);
				$this->setParam('endlevel', $endlevel);
				$this->beginMenu($startlevel, $endlevel);
				//Sub level
				$pid = $this->getParentId($startlevel - 1);
				if (@$this->children[$pid]) {
					foreach ($this->children[$pid] as $row) {
						if (@$this->children[$row->id]) {
							$this->genMenuItems ($row->id, $startlevel);
						} else {
							echo "<ul id=\"txdl-subnav{$row->id}\" class=\"clearfix\"><li class=\"empty\">&nbsp;</li></ul>";
						}
					}
				}
				$this->endMenu($startlevel, $endlevel);
			}
		}
		
		function genMenuItems1($pid, $level) {
			if (@$this->children[$pid]) {
				$this->beginMenuItems($pid, $level);
				$i = 0;
				foreach ($this->children[$pid] as $row) {
					$pos = ($i == 0 ) ? 'first' : (($i == count($this->children[$pid])-1) ? 'last' :'');

					$this->beginMenuItem($row, $level, $pos);
					$this->genMenuItem( $row, $level, $pos);

					// show menu with menu expanded - submenus visible
					if ($level < $this->getParam('endlevel')) $this->genMenuItems( $row->id, $level+1 );
					$i++;

					if ($level == 0 && $pos == 'last' && in_array($row->id, $this->open)) {
						global $jaMainmenuLastItemActive;
						$jaMainmenuLastItemActive = true;
					}
					$this->endMenuItem($row, $level, $pos);
				}
				$this->endMenuItems($pid, $level);
			} else if ($level==1){
				echo "<ul id=\"txdl-subnav$pid\" class=\"clearfix\"><li>&nbsp;</li></ul>";
			}
		}
		
        function beginMenuItems($pid=0, $level=0){
            if(!$level) echo "<ul>";
			else echo "<ul id=\"txdl-subnav$pid\" class=\"clearfix\">";
        }

        function beginMenuItem($mitem=null, $level = 0, $pos = ''){
			$active = $this->genClass ($mitem, $level, $pos);
			if ($active) $active = " class=\"$active clearfix\"";
            if(!$level) echo "<li id=\"txdl-mainnav{$mitem->id}\"$active>";
			else echo "<li id=\"txdl-subnavitem{$mitem->id}\"$active>";
        }

        function beginMenu($startlevel=0, $endlevel = 10){
            if(!$startlevel) echo "<div id=\"txdl-mainnav\">";
            else echo "<div id=\"txdl-subnav\">";			
        }

		function endMenu($startlevel=0, $endlevel = 10){
			echo "</div>";
			
		}

		function hasSubMenu($level) {
			return true;
		}
	}
}
?>
