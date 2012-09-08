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

    global $expose;
    $grid = $expose->getComponentWidth();
?>
    <jdoc:include type="message" />

<section id="mainbody" role="main" class="grid<?php echo $grid['component'];?> offset<?php echo $grid['sidebar-a'] + $grid['sidebar-b']; ?> clearfix">

    <?php /**Begin Content top**/ if($expose->countModules('contenttop')): ?>
    <!--Start Content Top Modules-->
    <section id="content-top" class="clearfix">
        <?php $expose->renderModules('contenttop', TRUE); ?>
    </section>
    <!--End Content top Modules-->
    <?php /**End Content top **/ endif;?>

    <?php if($expose->displayComponent()): ?>
    <section id="component" role="article" class="clearfix">

        <div class="block">
            <jdoc:include type="component" />
        </div>

    </section>
    <?php endif;?>

    <?php /**Begin Content bottom**/ if($expose->countModules('contentbottom')): ?>
    <!--Start Content Bottom Modules-->
    <section id="content-bottom" class="clearfix">
        <?php $expose->renderModules('contentbottom', TRUE); ?>
    </section>
    <!--End Content Bottom Modules-->
    <?php /**End Content bottom **/ endif;?>

</section>

<?php /**Begin Sidebar-A**/ if($expose->countModules('sidebar-a')): ?>
    <!--Start Sidebar-A Modules-->
    <aside id="sidebar-a" class="grid<?php echo $grid['sidebar-a'];?> inset<?php echo ($grid['component'] + $grid['sidebar-a'] + $grid['sidebar-b']) ?> clearfix" role="complementary">
        <?php $expose->renderModules('sidebar-a'); ?>
    </aside>
<!--End Sidebar-A Modules-->
<?php /**End Sidebar-A **/ endif;?>

<?php /**Begin Sidebar-B**/ if($expose->countModules('sidebar-b')): ?>
    <!--Start Sidebar-B Modules-->
    <aside id="sidebar-b" class="grid<?php echo $grid['sidebar-b'] ?> inset<?php echo ($grid['component'] + $grid['sidebar-b']) ?> clearfix" role="complementary">
        <?php $expose->renderModules('sidebar-b'); ?>
    </aside>
    <!--End Sidebar-B Modules-->
<?php /**End Sidebar-B **/ endif;?>
