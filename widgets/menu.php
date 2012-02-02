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

        $style = $this->get('style','mega');
        $hasSubMenu = '';

        if($expose->platform == 'mobile' AND $expose->browser->getBrowser() == 'iPhone')
        {
            $style = 'iphone';
        }

        $fileName = $style.'menu';


        switch($style){
            case 'dropline':
                $class = 'ExposeDroplineMenu';
                $hasSubMenu = TRUE;
                $expose->addLink('droplinemenu.css','css',2);
                break;

            case 'split':
                $class = 'ExposeSplitMenu';
                $hasSubMenu = TRUE;
                $expose->addLink('splitmenu.css','css',2);
                break;

            case 'iphone':
                $class = 'ExposeIphoneMenu';
                $hasSubMenu = FALSE;
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
        $expose->document->params->set('menu_images_align', 'left');
        $expose->document->params->set('menu_background', 1); //0: image, 1: background
        //$expose->document->params->set('mega-colwidth', 200); //Megamenu only: Default column width
        $expose->document->params->set('mega-style', 1); //Megamenu only: Menu style.
        $expose->document->params->set('startlevel', 0); //Startlevel
        $expose->document->params->set('endlevel', -1); //endlevel

        $menu = new $class($expose->document->params);

        ob_start();
        ?>
        <div id="ex-menu">
            <?php $menu->loadMenu(); ?>

            <?php $menu->genMenu(); ?>

            <?php if($hasSubMenu AND $menu->hasSubMenu(1) AND $menu->showSeparatedSub)
            { ?>
                <div id="ex-subnav" class="clearfix">
                   <?php $menu->genMenu(1); ?>
                </div>
           <?php
            } ?>
        <div class="clear"></div>
        </div>
        <?php
        return ob_get_clean();

    }

}
