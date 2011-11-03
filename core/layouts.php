<?php
/**
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        layouts.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

class ExposeLayouts {

    private $layoutType = NULL;
    private $layoutTemplPath = NULL;
    private $layoutExt = '.php';
    private $modules = array();
    
    public  $class = array();
    public  $contentBodyWidth = NULL;
    public  $sidebarLeftWidth = 0;
    public  $sidebarRightWidth = 0;

    public function __construct(){
    }
    
    public function count($position, $max){
        global $expose;
        if(self::checkMobilePosition($position)){
            $published_mod = 0;
            //if max is not define that mean its a standalone module
            if($max == 0){
                if($expose->doc->countModules($position)){
                    $expose->get('sidebar-left');
                    //$this->modules[$position][] = array('position'=>$position,'width'=>$expose->get($position));
                    $this->modules[$position][] = $expose->get($position);
                    return TRUE;
                }
            }
            //so this position contain many sub position like roof-1 roof-2 etc.
            for($i=1; $i<=$max; $i++){
                if($expose->doc->countModules($position. '-' . $i)){
                    //$this->modules[$position][$i]= array('position'=>$position. '-' . $i,'width'=>$expose->get($position. '-' . $i));
                    $this->modules[$position][$i] = $expose->get($position. '-' . $i);
                    $published_mod++;
                }
            }
            if($published_mod > 0) return $published_mod;
        }
    }

    public function render($type, $position=NULL, $chrome ='standard',$extra_classes=array()){
        global $expose;
        if($type === 'module'){
            //so its must have a position declearation
            if($position != NULL){
                $class = ' first';
                $i = 1;
                $total_mod = count($this->modules[$position]);
                $this->class = $extra_classes;

                if($position != 'sidebar-left' AND $position != 'sidebar-right'){
                    $this->calculateModuleWidth($position);
                }
                $positions = $this->modules[$position];
                //$this->generateModuleHtml($position, $positions, $chrome, $class);

                foreach($positions as $key=>$width){
                    if($position =='sidebar-left' OR
                       $position == 'sidebar-right' OR
                       $position == 'mobile-header' OR
                       $position =='mobile-footer'){

                        $this->generateModuleHtml ($position, 100, $chrome, $class);
                    }
                    else{
                        $this->generateModuleHtml($position.'-'.$key, $width, $chrome, $class);
                    }
                    $class = ($i+1 == $total_mod)? ' last' : '';
                    $i++;
                }

            }else{
                die(JText::_('ERROR_RENDER_MODULE'));
            }
        }
        elseif($type === 'body'){
            global $expose;
            if(isset($_COOKIE[$expose->templateName.'_layouts'])){
                $this->layoutType = $_COOKIE[$expose->templateName.'_layouts'];
            }
            else{
                if($this->isMobile()){
                    $this->layoutType = strtolower($expose->browser->getBrowser());
                }
                else{
                    switch ($expose->get('layout_type','left.content.right')) {
                        case '1':
                            $this->layoutType = 'left.content.right';
                            break;
                        case '2':
                            $this->layoutType = 'content.left.right';
                            break;
                        case '3':
                            $this->layoutType = 'left.right.content';
                            break;
                    }
                }
            }
            if(isset ($_REQUEST['layouts'])){
                setcookie($expose->templateName.'_layouts', $_REQUEST['layouts'], time()+3600, '/');
                $this->layoutType = $_REQUEST['layouts'];
            }
            
            $this->layoutTemplPath = $expose->templatePath . DS . 'layouts' . DS;
            //calculate mainbody width and put left and right into array if publish
            $this->calculateMainbodyWidth();
            
            if(file_exists($this->layoutTemplPath . $this->layoutType . $this->layoutExt)){
                include ($this->layoutTemplPath . $this->layoutType . $this->layoutExt);
            }
            else {
                include(EXPOSE_LAYOUT_PATH . DS . $this->layoutType . $this->layoutExt);
            }
        }
    }
    
    public function isMobile(){
        global $expose;
        if($expose->browser->isMobile() 
            AND ($expose->browser->getBrowser()==Browser::BROWSER_IPHONE OR $expose->browser->getBrowser()==Browser::BROWSER_ANDROID)
                AND ($expose->get('iphone_theme','0') == '1' OR $expose->get('android_theme','0') == '1'))
                {
            return TRUE;
        }
        return FALSE;
    }
    protected function calculateMainbodyWidth(){
        global $expose;
        $contentBodyWidth = 100;
        $this->count('sidebar-left',0);
        $this->count('sidebar-right',0);
        if(isset ($this->modules['sidebar-left'])){
            $contentBodyWidth -= $expose->get('sidebar-left','30');
            $this->sidebarLeftWidth = $expose->get('sidebar-left','30');
        }
        if(isset ($this->modules['sidebar-right'])){
            $contentBodyWidth -= $expose->get('sidebar-right','30');
            $this->sidebarRightWidth = $expose->get('sidebar-right','30');
        }
        $this->contentBodyWidth = $contentBodyWidth;
        
    }

    //TODO:this method is straight forward,should make it more programatic way
    protected function calculateModuleWidth($parentPosition){
        global $expose;
        $positions = $this->modules[$parentPosition];
        $mods = array();
        if(count($positions) == 1){
            $key = key($positions);
            $this->modules[$parentPosition][$key]= 100;
        }
        elseif(count($positions) == 2){
            $keys = array_keys($positions);
            $mod1 = (int)$positions[$keys[0]];
            $mod2 = (int)$positions[$keys[1]];

            if($mod1 == 100 OR $mod2 == 100){
                $mods[0]=$mods[1]=100;
//                if(($mod1+$mod2) > 160){
//                    $mods[0]=$mods[1]=100;
//                }
//                else{
//                    $mods = $this->adjustWidth($mod1, $mod2);
//                }
            }else{
                $mods = $this->adjustWidth($mod1, $mod2);
            }
            
            $this->modules[$parentPosition][$keys[0]]=$mods[0];
            $this->modules[$parentPosition][$keys[1]]=$mods[1];
        }
        elseif(count($positions) == 3){
            $keys = array_keys($positions);
            $mod1 = (int)$positions[$keys[0]];
            $mod2 = (int)$positions[$keys[1]];
            $mod3 = (int)$positions[$keys[2]];

            if($mod1 == 100){
                if($mod2 == 100 OR $mod3 == 100){
                    $mod1 = $mod2 = $mod3 = 100;
                }
                else{
                    $mods = $this->adjustWidth($mod2, $mod3);
                    $mod2 = $mods[0];
                    $mod3 = $mods[1];
                }
            }
            else if($mod2 == 100){
                $mod1 = $mod2 = $mod3 = 100;
            }
            else if($mod3 == 100){
                $mods = $this->adjustWidth($mod1, $mod2);
                $mod1 = $mods[0];
                $mod2 = $mods[1];
            }
            else{
                $sum = $mod1 + $mod2 + $mod3;
                $diff = NULL;
                if ($sum>100){
                    $diff = $sum - 100;
                    if($diff>=40){
                        $mods = $this->adjustWidth($mod1, $mod2);
                        $mod1 = $mods[0];
                        $mod2 = $mods[1];
                        $mod3 = 100;
                        $mods[2] = 100;
                    }
                    else{
                        //its greateer then 100 but its value less then 140 so make all 3 is equal width
                        $mod1 = 34;
                        $mod2 = 33;
                        $mod3 = 33;
                    }
                }
                else{
                    //so its less then 100, make first one is bigger.
                    $diffs = $this->equalDistribution(100-$sum);
                    $mod1 = $mod1+$diffs[0];
                    $mod2 = $mod2+$diffs[1];
                }

            }
            $this->modules[$parentPosition][$keys[0]]=$mod1;
            $this->modules[$parentPosition][$keys[1]]=$mod2;
            $this->modules[$parentPosition][$keys[2]]=$mod3;
        }
        elseif(count($positions)==4){
            $firstPos = 1;
            $lastPos  = 5;
            $currentPos = 1;
            foreach($positions as $position => $width){
                //check if last key not found execute this
                if(!array_key_exists($lastPos, $positions)){
                    $missingPosWidth = $expose->get($parentPosition.'-'.$lastPos);
                    if($missingPosWidth != 100){
                    //set this width to modules array
                    $this->modules[$parentPosition][$lastPos-1] = $missingPosWidth + $this->modules[$parentPosition][$lastPos-1];
                    return;
                    }
                }
                if(!array_key_exists($currentPos, $positions) ){
                    //so this position is missing
                    //get the missing module width
                    $missingPosWidth = $expose->get($parentPosition.'-'.$currentPos);
                    //make sure missing module is not full width, if it full width do nothing
                    if($missingPosWidth != 100){
                        //if missing position is odd number add this width to its next position
                        if($currentPos%2){
                            $this->modules[$parentPosition][$currentPos+1]= $missingPosWidth + $this->modules[$parentPosition][$currentPos+1];
                        }
                        //add missing module width its previous position
                        else{
                            $this->modules[$parentPosition][$currentPos-1]= $missingPosWidth + $this->modules[$parentPosition][$currentPos-1];
                        }

                    }
                    
                }
                $currentPos++;
            }
        }


    }

    private function adjustWidth($mod1,$mod2){
        $mod1 = (int)$mod1;
        $mod2 = (int)$mod2;
        $mods = array();
        $diffs = array();
        if($mod1 + $mod2 > 100){
            if($mod1 >= 70 AND $mod2 >=70){
                $mod1 = $mod2 = 100;
            }
            else{
                $diff = ($mod1+$mod2) - 100;
                $diffs = $this->equalDistribution($diff);
                $mod1 = $mod1 - $diffs[0];
                $mod2 = $mod2 - $diffs[1];
            }
        }
        else{
            $diff = 100 - ($mod1+$mod2);
            $diffs = $this->equalDistribution($diff);
            $mod1 = $mod1+$diffs[0];
            $mod2 = $mod2+$diffs[1];
        }
        $mods[]= $mod1;
        $mods[]= $mod2;
        return $mods;
    }

    protected function equalDistribution($val){
        $diffs = array();
        //odd number
        if($val%2){
            $diffs[] = ceil($val/2);
            $diffs[] = floor($val/2);
        }
        //for even value
        else{
            $diffs[0] = $val/2 ;
            $diffs[1] = $val/2;

        }
        return $diffs;
    }

    protected function checkMobilePosition($position){
        //if come from mobile device only load mobile positions
        if(self::isMobile()){
            //only mobile position will countable
            if(preg_match('/^mobile/', $position)){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        else{
            //if come from desktop device and want to load mobile postion deny it
            if(preg_match('/^mobile/', $position)){
                return FALSE;
            }
            else{
                return TRUE;
            }
        }
    }

    public function getModules(){
        return $this->modules;
    }

    private function generateModuleHtml($position, $width, $chrome, $class){
            //global $expose;
            $additional_class = (count($this->class)>0) ? ' '. array_shift($this->class) : '';
        ?>
<div class="tx-mod<?php echo $additional_class;?><?php echo $class;?>" style="width:<?php echo $width;?>%">
            <div class="mod-inner">
                <jdoc:include type="modules" name="<?php echo $position;?>" style="<?php echo $chrome?>" />
            </div>
        </div>
        <?php
    }
}

