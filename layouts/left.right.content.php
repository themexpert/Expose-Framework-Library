<?php
/**
 * Description of left
 *
 * @package     Expose
 * @version     2.0    Mar 23, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        left.right.content
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

$display_component = !($expose->isHomePage() AND $expose->get('component_display')==FALSE );
?>
<div class="tx-main-inner">

    <jdoc:include type="message" />
    
    <?php /*Begin Sidebar Left*/ if(isset ($expose->layout->modules['sidebar-left'])):?>
        <div id="sidebar-left" class="sidebar" style="width: <?php echo $expose->layout->sidebarLeftWidth?>%">
            <?php $expose->renderModules('sidebar-left','standard');?>
        </div>
    <?php /*End Sidebar Left*/ endif;?>

    <?php /*Begin Sidebar Right*/ if(isset ($expose->layout->modules['sidebar-right'])):?>
        <div id="sidebar-right" class="sidebar" style="width: <?php echo $expose->layout->sidebarRightWidth?>%">
            <?php $expose->renderModules('sidebar-right','standard');?>
        </div>
    <?php /*End Sidebar Right*/ endif;?>

    <?php if($display_component) :?>
    <div id="tx-mainbody" style="width: <?php echo $expose->layout->contentBodyWidth;?>%">
        <div class="tx-content">
            <?php /**Begin ContentTop**/ if($expose->countModules('content-top',4)): ?>
            <!--Start Content Top Modules-->
            <div id="tx-content-top">
                <?php $expose->renderModules('content-top','standard',array(/*pass the class name seperate by , */)); ?>
                <div class="clear"></div>
            </div>
            <!--End Content Top Modules-->
            <?php /**End Content Top**/ endif;?>

            <jdoc:include type="component" />

            <?php /**Begin ContentBottom**/ if($expose->countModules('content-bottom',4)): ?>
            <!--Start Content Bottom Modules-->
            <div id="tx-content-bottom">
                <?php $expose->renderModules('content-bottom','standard',array(/*pass the class name seperate by , */)); ?>
                <div class="clear"></div>
            </div>
            <!--End Content Bottom Modules-->
            <?php /**End Content Bottom**/ endif;?>
        </div>
    </div>
<<<<<<< .mine
     <?php endif;?>
=======
    <div class="clear"></div>
>>>>>>> .r87
</div>

