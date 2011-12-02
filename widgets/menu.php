<?php

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetMenu extends ExposeWidget{

    public $name = 'menu';

    public function render()
    {
        $menuType = $this->get('type');
        $hasSubMenu = '';
        $fileName = $menuType.'menu';

        switch($menuType){
            case 'dropline':
                $class = 'ExposeDroplineMenu';
                $hasSubMenu = TURE;
                break;
            case 'split':
                $class = 'ExposeSplitMenu';
                $hasSubMenu = TRUE;
                break;
            case 'mega':
            default:
                $class = 'ExposeMegaMenu';
                $hasSubMenu = FALSE;

                //load xpertmenu aka mega menu js file
                $this->document->addScript($this->exposeUrl.'/interface/js/xpertmenu.js');
                $js = "jQuery('#tx-menu').XpertMenu({
                    action:'mouseenter',
                    parent:'.tx-container',
                    hideDelay:'300',
                    transition:'slide',
                    easing:'easeInOutExpo'
                });";

                $this->addjQDom($js);
                break;
        }
        //import menu file
        expose_import("core.menu.$fileName");

        $menu = new $class($this->document->params);

        echo "<div id='tx-menu'>";
            $menu->loadMenu($this->get('name'));

            $menu->genMenu(0,-1);

            if($hasSubMenu)
            {
                echo "<div id='tx-subnav' class='clearfix'>";
                   $menu->genMenu(1);
                echo "</div>";
            }

        echo "</div>";

    }

}
