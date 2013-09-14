<?php

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetSplitmenu extends ExposeWidget{

    public $name = 'splitmenu';
    public $menu;


    public function isEnabled()
    {
        global $expose;

        // If menu style is not split eject
        $style = ( isset($_COOKIE[$expose->templateName.'_menu']) ) ? $_COOKIE[$expose->templateName.'_menu'] : '';
        if( $style !== 'split' AND $expose->get('menu-style') !== 'split') return FALSE; 

        if( !class_exists('ExposeSplitMenu') )
        {
           //import menu file
           expose_import("core.menu.splitmenu");
        }

        $this->menu = new ExposeSplitMenu($expose->document->params);
        $this->menu->loadMenu();

        if( $this->menu->hasSubMenu(1) AND $this->menu->showSeparatedSub )
        {
            return TRUE;
        }

        return FALSE;
    }

    public function render()
    {
        ob_start();
        ?>
        <?php if($this->get('title')):?>
        <div class="header hidden-phone">
            <h2 class="title"><?php echo $this->get('title-text', 'Sub Menu');?></h2>
        </div>  
        <?php endif;?>

            <?php if( $this->menu->hasSubMenu(1) AND $this->menu->showSeparatedSub )
            { ?>
                <div id="ex-splitmenu" class="<?php echo $this->get('style') ;?> hidden-phone">
                   <?php $this->menu->genMenu(1); ?>
                </div>
           <?php
            } ?>

        <?php
        return ob_get_clean();

    }

}
