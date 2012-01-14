<?php
/**
 * @package     Expose
 * @version     3.0.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

    global $expose;
    $width = $expose->getComponentWidth();
?>

<div id="ex-main" class="ex-row">

    <jdoc:include type="message" />

    <div id="ex-mainbody" class="ex-column" style="width:<?= $width['component'];?>%;">

        <?php /**Begin Content top**/ if($expose->countModules('contenttop')): ?>
        <!--Start Content Top Modules-->
        <div id="ex-contenttop">
            <?php $expose->renderModules('contenttop'); ?>
            <div class="clear"></div>
        </div>
        <!--End Content top Modules-->
        <?php /**End Content top **/ endif;?>
        
        <?php if($expose->displayComponent()) :?>
        <div id="ex-content" role="article">
            <div class="ex-container">
                <div class="ex-block">
                    <jdoc:include type="component" />
                </div>
            </div>
        </div>
        <?php endif;?>

        <?php /**Begin Content bottom**/ if($expose->countModules('contentbottom')): ?>
        <!--Start Content Bottom Modules-->
        <div id="ex-contentbottom">
            <?php $expose->renderModules('contentbottom'); ?>
            <div class="clear"></div>
        </div>
        <!--End Content Bottom Modules-->
        <?php /**End Content bottom **/ endif;?>

    </div>

    <?php /**Begin Sidebar-A**/ if($expose->countModules('sidebar-a')): ?>

    <!--Start Sidebar-A Modules-->
    <div id="ex-sidebar-a" class="ex-column" role="complementary" style="width:<?= $expose->getSidebarsWidth('sidebar-a') ?>%;">
        <?php $expose->renderModules('sidebar-a'); ?>
        <div class="clear"></div>
    </div>

    <!--End Sidebar-A Modules-->
    <?php /**End Sidebar-A **/ endif;?>

    <?php /**Begin Sidebar-B**/ if($expose->countModules('sidebar-b')): ?>

        <!--Start Sidebar-B Modules-->
        <div id="ex-sidebar-b" class="ex-column" role="complementary" style="width:<?= $expose->getSidebarsWidth('sidebar-b') ?>%;">
            <?php $expose->renderModules('sidebar-b'); ?>
            <div class="clear"></div>
        </div>
        <!--End Sidebar-B Modules-->
    <?php /**End Sidebar-B **/ endif;?>

    <div class="clear"></div>

</div>