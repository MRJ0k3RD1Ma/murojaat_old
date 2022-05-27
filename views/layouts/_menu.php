<?php

use yii\helpers\Url; ?>
<!-- Sidebar Menu -->



<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li>
            <a href="<?= Yii::$app->urlManager->createUrl(['/site/profile'])?>" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    <?= Yii::$app->user->identity->name?>
                </p>
            </a>
        </li>

        <?php
        $user = Yii::$app->user->identity;
        if($user->is_registration){?>
            <?= $this->render('_menu_registrator')?>
        <?php }else{?>

            <?= $this->render('_menu_user')?>

        <?php }?>

    </ul>
</nav>
<!-- /.sidebar-menu -->

<style>
    .nav-treeview{
        background: rgb(45, 82, 163) !important;
    }
</style>

