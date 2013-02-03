<?php

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetMenu extends ExposeWidget{

    public $name = 'menu';

    public function render()
    {
        global $expose;
        $html = '';
        $action = $this->get('action');
        $delay = $this->get('delay', 300);
        $animation = $this->get('animation');
        $fancy = ($this->get('fancy-animation')) ? 'true' : 'false';

        $style = (isset ($_COOKIE[$expose->templateName.'_menu'])) ? $_COOKIE[$expose->templateName.'_menu'] : $this->get('style','mega');
        if(isset ($_REQUEST['menu'])){
            setcookie($expose->templateName.'_menu',$_REQUEST['menu'],time()+3600,'/');
            $style = $_REQUEST['menu'];
        }

        //$style = $this->get('style','mega');
        $hasSubMenu = '';

        $fileName = $style.'menu';


        switch($style){
            case 'dropline':
                $class = 'ExposeDroplineMenu';
                $hasSubMenu = TRUE;
                $expose->addLink('droplinemenu.css','css',2);
                //load dropline menu js file
                $expose->addLink('droplinemenu.js','js');
                break;

            case 'split':
                $class = 'ExposeSplitMenu';
                $hasSubMenu = TRUE;
                $expose->addLink('splitmenu.css','css',2);
                break;

            case 'mega':
            default:
                $class = 'ExposeMegaMenu';
                $hasSubMenu = FALSE;

                $expose->addLink('megamenu.css','css',2);

                //load xpertmenu aka mega menu js file
                $expose->addLink('xpertmenu.js','js');

                $js = "
                var _options = {
                    _hideDelay:{$delay},
                    _easing:'{$animation}',
                    _isFancy:{$fancy}
                };
                jQuery('#megamenu').XpertMenu(_options);
                ";

                $expose->addjQDom($js);
                break;
        }

        //import menu file
        expose_import("core.menu.$fileName");

        //set some menu params
        $expose->document->params->set('menu_background', 1); //0: image, 1: background
        $align = ($expose->direction == 'rtl') ? 'right' : 'left';
        $expose->document->params->set('menu_images_align', $align); //applicapbe when selected as image
        //$expose->document->params->set('mega-colwidth', 200); //Megamenu only: Default column width
        $expose->document->params->set('mega-style', 1); //Megamenu only: Menu style.
        $expose->document->params->set('startlevel', 0); //Startlevel
        $expose->document->params->set('endlevel', -1); //endlevel

        $menu = new $class($expose->document->params);

        ob_start();
        ?>

        <nav id="menu" class="hidden-phone">

            <?php $menu->loadMenu(); ?>

            <?php $menu->genMenu(); ?>

            <?php if($hasSubMenu AND $menu->hasSubMenu(1) AND $menu->showSeparatedSub)
            { ?>
                <div id="subnav" class="clearfix">
                   <?php $menu->genMenu(1); ?>
                </div>
           <?php
            } ?>

        </nav> <!-- menu end -->

        <nav id="mobile-menu" class="visible-phone">
            <select onChange="window.location.replace(this.options[this.selectedIndex].value)">
                <?php foreach($menu->items as $key => $val):?>
                <?php
                    $itemId = JRequest::getvar('Itemid');
                    $active = '';
                   if ($itemId == $val->id) $active = 'selected="selected"';
                ?>
                <option value="<?php echo $val->url;?>" <?php echo $active; ?> >
                    <?php
                        if( count($val->tree) > 1 )
                        {
                            for($i=0; $i < (count($val->tree)-1); $i++){
                                echo "-";
                            }
                        }
                    ?>
                    <?php echo $val->title; ?>
                </option>
                <?php endforeach;?>
            </select>
        </nav>

        <?php
        return ob_get_clean();
    }

}
