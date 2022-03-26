<?php

use yii\helpers\Url; ?>
<!-- Sidebar Menu -->



<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="/" class="nav-link <?=(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index')?'active':''?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Бош саҳифа
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/company'])?>" class="nav-link <?=(Yii::$app->controller->id=='company' && Yii::$app->controller->action->id=='index')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Ташкилотлар
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/company-type'])?>" class="nav-link <?=(Yii::$app->controller->id=='company-type')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Ташкилотлар турлари
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/bulim'])?>" class="nav-link <?=(Yii::$app->controller->id=='bulim')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Бўлимлар
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/lavozim'])?>" class="nav-link <?=(Yii::$app->controller->id=='lavozim')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Лавозимлар
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/appeal-question-group'])?>" class="nav-link <?=(Yii::$app->controller->id=='appeal-question-group')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Савол гуруҳлари
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/admin/appeal-question'])?>" class="nav-link <?=(Yii::$app->controller->id=='appeal-question')?'active':''?>">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Саволлар
                </p>
            </a>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->
