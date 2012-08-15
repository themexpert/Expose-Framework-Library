<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

    global $expose;
    $grid = $expose->getComponentWidth();
?>
<div id="main" class="container">

    <div class="row">
    <jdoc:include type="message" />

    <div id="mainbody" class="grid<?php echo $grid['component'];?> offset<?php echo $grid['sidebar-a']; ?>">

        <?php /**Begin Content top**/ if($expose->countModules('contenttop')): ?>
        <!--Start Content Top Modules-->
        <div id="content-top" class="row">
            <?php $expose->renderModules('contenttop', TRUE); ?>
        </div>
        <!--End Content top Modules-->
        <?php /**End Content top **/ endif;?>

        <?php if($expose->displayComponent()): ?>
        <div id="component" role="article" class="row">

            <div class="block">
                <jdoc:include type="component" />
            </div>

        </div>
        <?php endif;?>

        <?php /**Begin Content bottom**/ if($expose->countModules('contentbottom')): ?>
        <!--Start Content Bottom Modules-->
        <div id="content-bottom" class="row">
            <?php $expose->renderModules('contentbottom', TRUE); ?>
        </div>
        <!--End Content Bottom Modules-->
        <?php /**End Content bottom **/ endif;?>

    </div>

    <?php /**Begin Sidebar-A**/ if($expose->countModules('sidebar-a')): ?>

    <!--Start Sidebar-A Modules-->
    <div id="sidebar-a" class="grid<?php echo $grid['sidebar-a'];?> inset<?php echo ($grid['component'] + $grid['sidebar-a']) ?>" role="complementary">
        <?php $expose->renderModules('sidebar-a'); ?>
    </div>

    <!--End Sidebar-A Modules-->
    <?php /**End Sidebar-A **/ endif;?>

    <?php /**Begin Sidebar-B**/ if($expose->countModules('sidebar-b')): ?>

        <!--Start Sidebar-B Modules-->
        <div id="sidebar-b" class="grid<?php echo $grid['sidebar-b'] ?>" role="complementary">
            <?php $expose->renderModules('sidebar-b'); ?>
        </div>
        <!--End Sidebar-B Modules-->
    <?php /**End Sidebar-B **/ endif;?>
    </div>
</div>