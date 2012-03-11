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

        if($expose->platform == 'mobile')
        {
            $style = 'iphone';
        }

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

            case 'iphone':
                $class = 'ExposeIphoneMenu';
                $hasSubMenu = FALSE;

                $expose->addLink('iphone.js','js',1);

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
                jQuery('#ex-megamenu').XpertMenu(_options);
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

        <div id="ex-menu">
            <?php if($expose->platform == 'mobile'): ?>
            <div id="ex-toolbar">
            <div id="ex-toolbar-top">
                <div class="nav-btns">
                    <div class="links">
                        <a class="toggle button btn-menu ip-button" href="#ex-iphonemenu" title="Menu">Menu</a>
                    </div>
                </div>
            </div>

            <div id="ex-toolbar-main">
                <div id="ex-toolbar-wrap">
                    <div id="ex-toolbar-title">
                        <a class="button btn-back" href="#" id="toolbar-back" title=""></a>
                        <span id="toolbar-title">&nbsp;</span>
                        <a class="button btn-close" href="#" id="toolbar-close" title="">Close</a>
                    </div>
            <?php endif; ?>

            <?php $menu->loadMenu(); ?>

            <?php $menu->genMenu(); ?>

            <?php if($hasSubMenu AND $menu->hasSubMenu(1) AND $menu->showSeparatedSub)
            { ?>
                <div id="ex-subnav" class="clearfix">
                   <?php $menu->genMenu(1); ?>
                </div>
           <?php
            } ?>

            <?php if($expose->platform == 'mobile'):?>
                </div><!-- ex-toolbar-wrap end -->
            </div> <!-- ex-toolbar-main end -->
            </div>
            <div id="ex-overlay">&nbsp;</div>
            <?php endif;?>

        </div> <!-- ex-menu end -->


        <?php
        return ob_get_clean();

    }

}
