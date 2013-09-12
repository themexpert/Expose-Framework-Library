<?php
/**
 * @package     Expose
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetOffcanvas extends ExposeWidget{

    public $name = 'offcanvas';

    public function isEnabled()
    {
        return TRUE;
    }

    public function getPosition()
    {
        return 'offcanvas';
    }

    public function render()
    {
        global $expose;

        //import menu file
        expose_import("core.menu.accordion");

        // Assign some values
        $startlevel = $this->get('startlevel', 0);
        $endlevel = $this->get('endlevel', 3);
        
        // Set start and end level to params
        $expose->document->params->set('startlevel', $startlevel); //Startlevel
        $expose->document->params->set('endlevel', $endlevel); //endlevel

        $menu = new ExposeAccordionMenu($expose->document->params);

        // Load offcanvas js
        $expose->addLink('offcanvas.js','js');

        ob_start();
        ?>
            <nav class="ex-menu">

                <?php $menu->loadMenu(); ?>
                <?php $menu->genMenu(); ?>

            </nav> <!-- menu end -->

        <?php
        return ob_get_clean();
    }
}
?>
