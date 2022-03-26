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
        <li class="nav-item">
            <a href="/" class="nav-link <?=(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index')?'active':''?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Менинг топшириқларим
                </p>
            </a>
        </li>
        <?php if(Yii::$app->user->identity->is_registration == 1){?>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/index'])?>" class="nav-link <?=(Yii::$app->controller->id=='appeal' and Yii::$app->controller->action->id != 'companies')?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Мурожаатлар
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/companies'])?>" class="nav-link <?=(Yii::$app->controller->id=='appeal' and Yii::$app->controller->action->id == 'companies')?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Ташкилотлар
                    </p>
                </a>
            </li>

        <?php }?>

        <?php if(Yii::$app->user->identity->is_control == 1){?>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/index'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and (Yii::$app->controller->action->id=='index' or Yii::$app->controller->action->id=='view'))?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Назорат
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/rahbar'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and (Yii::$app->controller->action->id=='rahbar' or Yii::$app->controller->action->id=='listrahbar' or Yii::$app->controller->action->id=='viewrahbar' ))?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Раҳбарлар
                    </p>
                </a>
            </li>
        <?php }?>


        <?php if(Yii::$app->user->identity->is_control_district == 1){?>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/village'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and Yii::$app->controller->action->id=='village')?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Маҳалла кесимида
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/company'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and Yii::$app->controller->action->id=='company')?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Ташкилотлар кесимида
                    </p>
                </a>
            </li>

        <?php }?>


        <?php if(Yii::$app->user->identity->is_control_system == 1){?>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/district'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and (Yii::$app->controller->action->id=='district' or Yii::$app->controller->action->id=='village'))?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Туманлар кесимида
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/control/companies'])?>" class="nav-link <?=(Yii::$app->controller->id=='control' and (Yii::$app->controller->action->id=='companies' or Yii::$app->controller->action->id=='company'))?'active':''?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Ташкилотлар кесимида
                    </p>
                </a>
            </li>
        <?php }?>

    </ul>
</nav>
<!-- /.sidebar-menu -->
