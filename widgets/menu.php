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

        $menuStyle = $this->get('style','mega');
        $hasSubMenu = '';
        $fileName = $menuStyle.'menu';

        if($expose->platform == 'mobile' AND $expose->browser->getBrowser == 'iPhone')
        {
            $menuStyle = 'iphone';
        }

        switch($menuStyle){
            case 'dropline':
                $class = 'ExposeDroplineMenu';
                $hasSubMenu = TURE;
                break;

            case 'split':
                $class = 'ExposeSplitMenu';
                $hasSubMenu = TRUE;
                break;

            case 'iphone':
                $class = 'ExposeIphoneMenu';
                $hasSubMenu = FALSE;
                break;

            case 'mega':
            default:
                $class = 'ExposeMegaMenu';
                $hasSubMenu = FALSE;

                $expose->addLink('menu.css','css');
                //load xpertmenu aka mega menu js file
                $expose->addLink('xpertmenu.js','js');
                $js = "$('#ex-megamenu').XpertMenu({
                    action:'mouseenter',
                    parent:'#ex-menu',
                    hideDelay:'300',
                    transition:'slide',
                    easing:'easeInOutExpo'
                });";

                $expose->addjQDom($js);
                break;
        }
        //import menu file
        expose_import("core.menu.$fileName");

        $menu = new $class($expose->document->params);
        ob_start();
        ?>
        <div id='ex-menu'>
            <?php $menu->loadMenu(); ?>

            <?php $menu->genMenu(); ?>

            <?php if($hasSubMenu)
            { ?>
                <div id='tx-subnav' class='clearfix'>
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
