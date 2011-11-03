<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        megamenu.php
 *
 * This modified class is based on T3 GPL Framework.
 **/

defined('_JEXEC') or die('Restricted access');
if (!defined ('_EXPOSE_MEGA_MENU')) {
	define ('_EXPOSE_MEGA_MENU', 1);
	require_once (dirname(__FILE__).DS."basemenu.php");

	class ExposeMegaMenu extends ExposeBaseMenu{

		function __construct (&$params) {
			$params->set('megamenu', 1);
			parent::__construct($params);
			if (!$this->getParam('menuname')) $this->setParam('menuname', 'tx-megamenu');
		}

		function beginMenu($startlevel=0, $endlevel = 10){
			echo "<div class=\"tx-megamenu clearfix\" id=\"".$this->getParam('menuname')."\">\n";
		}
		function endMenu($startlevel=0, $endlevel = 10){
			//If rtl, not allow slide and fading effect
			$rtl = $this->getParam ('rtl');
			/*$animation = $this->_tmpl->getParam ('ja_menu_mega_animation', 'none');
			$duration = $this->_tmpl->getParam ('ja_menu_mega_duration', 300);
			$delayHide = $this->_tmpl->getParam ('ja_menu_mega_delayhide', 300);
			$fade = 0;
			$slide = 0;*/
			/*if (!$rtl) {
				if (preg_match ('/slide/', $animation)) $slide = 1;
				if (preg_match ('/fade/', $animation)) $fade = 1;
			}*/
			echo "\n</div>";
			//Create menu
			?>
			<!--<script type="text/javascript">
			var megamenu = new jaMegaMenuMoo ('<?php /*echo $this->getParam('menuname') */?>', {
				'bgopacity': 0, 
				'delayHide': <?php /*echo $delayHide */?>,
				'slide': <?php /*echo $slide */?>,
				'fading': <?php /*echo $fade */?>,
				'direction':'down',
				'action':'mouseover',
				'tips': false,
				'duration': <?php /*echo $duration */?>,
				'hidestyle': 'fastwhenshow'
			});			
			</script>-->
			<?php
		}
		function beginMenuItems($pid=0, $level=0, $return=false){
			if ($level) {
				if ($this->items[$pid]->megaparams->get('group')) {
					$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;				
					$cols_cls = ($cols > 1)?" cols$cols":'';
					$data = "<div class=\"group-content$cols_cls\">";
				} else {
					$style = $this->getParam ('mega-style', 1);
					if (!method_exists($this, "beginMenuItems$style")) $style = 1; //default
					$data = call_user_func_array(array($this, "beginMenuItems$style"), array ($pid, $level, true));
				}
				if ($return) return $data; else echo $data;
			}
		}
		function endMenuItems($pid=0, $level=0, $return=false){
			if ($level) {
				if ($this->items[$pid]->megaparams->get('group')) {
					$data = "</div>";
				}else{
					$style = $this->getParam ('mega-style', 1);
					if (!method_exists($this, "endMenuItems$style")) $style = 1; //default
					$data = call_user_func_array(array($this, "endMenuItems$style"), array ($pid, $level, true));
				}
				if ($return) return $data; else echo $data;
			}
		}			
		function beginSubMenuItems($pid=0, $level=0, $pos, $i, $return = false){
			$data = '';
			if (isset ($this->items[$pid]) && $level) {
				$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;		
				if ($this->items[$pid]->megaparams->get('group') && $cols < 2) {
				}else {
					$colw = $this->items[$pid]->megaparams->get('colw'.($i+1), 0);
					if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('mega-colwidth',200));
					if(is_null($colw) || !is_numeric($colw)) $colw = 200;
					$style = $colw?" style=\"width: {$colw}px;\"":"";
					$data .= "<div class=\"megacol column".($i+1).($pos?" $pos":"")."\"$style>";
				}
			}
			if (@$this->children[$pid]) $data .= "<ul class=\"megamenu level$level\">";
			if ($return) return $data; else echo $data;
		}
		function endSubMenuItems($pid=0, $level=0, $return = false){
			$data = '';
			if (@$this->children[$pid]) $data .= "</ul>";
			if (isset ($this->items[$pid]) && $level) {
				$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;		
				if ($this->items[$pid]->megaparams->get('group') && $cols < 2) {
				}else
					$data .= "</div>";
			}
			if ($return) return $data; else echo $data;
		}
		
		function beginSubMenuModules($item, $level=0, $pos, $i, $return = false){
			$data = '';
			if ($level) {
				if ($item->megaparams->get('group')) {
				}else {
					$colw = $item->megaparams->get('colw'.($i+1), 0);
					if (!$colw) $colw = $item->megaparams->get('colw', $this->getParam ('mega-colwidth',200));
					$style = $colw?" style=\"width: {$colw}px;\"":"";
					$data .= "<div class=\"megacol column".($i+1).($pos?" $pos":"")."\"$style>";
				}
			}
			if ($return) return $data; else echo $data;
		}
		
		function endSubMenuModules($item, $level=0, $return = false){
			$data = '';
			if ($level) {
				if ($item->megaparams->get('group')) {
				}else
					$data .= "</div>";
			}
			if ($return) return $data; else echo $data;
		}

		function genClass ($mitem, $level, $pos) {
			$iParams = new JParameter ( $mitem->params );
			$cls = "mega".($pos?" $pos":"");
			if (@$this->children[$mitem->id] || (isset($mitem->content) && $mitem->content)) {
				if ($mitem->megaparams->get('group')) $cls .= " group";
				else if ($level < $this->getParam('endlevel')) $cls .= " haschild";
			}
			
			$active = in_array($mitem->id, $this->open);
			if (!preg_match ('/group/', $cls)) $cls .= ($active?" active":"");
			if ($mitem->megaparams->get('class')) $cls .= " ".$mitem->megaparams->get('class');
			return $cls;
		}
		
		function beginMenuItem($mitem=null, $level = 0, $pos = ''){
			$active = $this->genClass ($mitem, $level, $pos);
			if ($active) $active = " class=\"$active\"";
			echo "<li $active>";
			if ($mitem->megaparams->get('group')) echo "<div class=\"group\">";
		}
		function endMenuItem($mitem=null, $level = 0, $pos = ''){
			if ($mitem->megaparams->get('group')) echo "</div>";
			echo "</li>";
		}		
		
		/*Sub nav style - 1 - basic (default)*/
		function beginMenuItems1($pid=0, $level=0, $return=false){
			$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
			$width = $this->items[$pid]->megaparams->get('width', 0);
			if (!$width) {
				for ($col=0;$col<$cols;$col++) {
					$colw = $this->items[$pid]->megaparams->get('colw'.($col+1), 0);
					if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('mega-colwidth',200));
					if(is_null($colw) || !is_numeric($colw)) $colw = 200;
					$width += $colw;
				}
			}
			$style = $width?" style=\"width: {$width}px;\"":"";
			$right = $this->items[$pid]->megaparams->get('right') ? 'right':'';
			$data = "<div class=\"childcontent cols$cols $right\">\n";
			$data .= "<div class=\"childcontent-inner-wrap\">\n"; 	//Add wrapper
			$data .= "<div class=\"childcontent-inner clearfix\"$style>"; //Move width into inner
			if ($return) return $data; else echo $data;
		}
		function endMenuItems1($pid=0, $level=0, $return=false){
			$data = "</div>\n"; //Close of childcontent-inner
			$data .= "</div></div>"; //Close wrapper and childcontent
			if ($return) return $data; else echo $data;
		}
		/*Sub nav style - 2 - advanced*/
		function beginMenuItems2($pid=0, $level=0, $return=false){
			$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
			
			$width = $this->items[$pid]->megaparams->get('width', 0);
			if (!$width) {
				for ($col=0;$col<$cols;$col++) {
					$colw = $this->items[$pid]->megaparams->get('colw'.($col+1), 0);
					if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('mega-colwidth',200));
					$width += $colw;
				}
			}
			$style = $width?" style=\"width: {$width}px;\"":"";
			$right = $this->items[$pid]->megaparams->get('right') ? 'right':'';
			$data = "<div class=\"childcontent cols$cols $right\">\n";
			$data .= "<div class=\"childcontent-inner-wrap\">\n"; 	//Add wrapper
			$data .= "<div class=\"l\"></div>\n";	//Left border
			$data .= "<div class=\"childcontent-inner clearfix\"$style>"; //Childcontent-inner - Move width into inner
			if ($return) return $data; else echo $data;
		}
		function endMenuItems2($pid=0, $level=0, $return=false){
			$data = "</div>\n"; //Close of childcontent-inner
			$data .= "<div class=\"r\" ></div>\n"; //Right border
			$data .= "</div></div>"; //Close wrapper and childcontent
			if ($return) return $data; else echo $data;
		}
		/*Sub nav style - 3 - complex*/
		function beginMenuItems3 ($pid=0, $level=0, $return=false){
			$cols = $pid && $this->getParam('megamenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
			
			$width = $this->items[$pid]->megaparams->get('width', 0);
			if (!$width) {
				for ($col=0;$col<$cols;$col++) {
					$colw = $this->items[$pid]->megaparams->get('colw'.($col+1), 0);
					if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('mega-colwidth',200));
					$width += $colw;
				}
			}
			$style = $width?" style=\"width: {$width}px;\"":"";
			$right = $this->items[$pid]->megaparams->get('right') ? 'right':'';
			$data = "<div class=\"childcontent cols$cols $right\">\n";
			$data .= "<div class=\"childcontent-inner-wrap\">\n"; 	//Add wrapper
			$data .= "<div class=\"top\" ><div class=\"tl\"></div><div class=\"tr\"></div></div>\n";	//Top
			$data .= "<div class=\"mid\">\n"; //Middle
			$data .= "<div class=\"ml\"></div>\n"; //Middle left
			$data .= "<div class=\"childcontent-inner clearfix\"$style>"; //Move width into inner
			if ($return) return $data; else echo $data;
		}
		function endMenuItems3($pid=0, $level=0, $return=false){
			$data = "</div>\n"; //Close of childcontent-inner
			$data .= "<div class=\"mr\"></div>\n"; //Middle right
			$data .= "</div>"; //Close Middle
			$data .= "<div class=\"bot\" ><div class=\"bl\"></div><div class=\"br\"></div></div>\n";	//Bottom
			$data .= "</div></div>"; //Close wrapper and childcontent
			if ($return) return $data; else echo $data;
		}
	}
}