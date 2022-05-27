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


        <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-filter"></i>
                <p>
                    Менинг топшириқларим
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="/" class="nav-link <?=(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index')?'active':''?>">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Мурожаатлар рўйхати
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/site/companies'])?>" class="nav-link <?=(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='companies')?'active':''?>">
                        <i class="nav-icon fas fa-route"></i>
                        <p>
                            Ташкилотлар
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Yii::$app->urlManager->createUrl(['#'])?>" class="nav-link <?=(Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='answered')?'active':''?>">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Жавоби келган
                        </p>
                    </a>
                </li>

            </ul>
        </li>

        <?php if(Yii::$app->user->identity->is_registration == 1){?>
            <li class="nav-item">

            </li>

            <li class="nav-item has-treeview">
                <a href="#" class="nav-link ">
                    <i class="nav-icon fas fa-filter"></i>
                    <p>
                        Мурожаатлар
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/notregister'])?>" class="nav-link
                <?=(Yii::$app->controller->id=='appeal'
                            and Yii::$app->controller->action->id == 'notregister'
                        )?'active':''?>">
                            <i class="nav-icon fas fa-registered"></i>
                            <p>
                                Рўйхатга олинмаган
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/index'])?>" class="nav-link
                <?=(Yii::$app->controller->id=='appeal'
                            and Yii::$app->controller->action->id != 'companies'
                            and Yii::$app->controller->action->id != 'request'
                            and Yii::$app->controller->action->id != 'notregister'
                        )?'active':''?>">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Мурожаатлар рўйхати
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/answered'])?>" class="nav-link
                <?=(Yii::$app->controller->id=='appeal'
                            and Yii::$app->controller->action->id != 'answered'
                        )?'active':''?>">
                            <i class="nav-icon fas fa-check"></i>
                            <p>
                                Натижаси келган
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/index'])?>" class="nav-link
                <?=(Yii::$app->controller->id=='appeal'
                            and Yii::$app->controller->action->id != 'companies'
                            and Yii::$app->controller->action->id != 'request'
                        )?'active':''?>">
                            <i class="nav-icon fas fa-plus"></i>
                            <p>
                                Янги қўшиш
                            </p>
                        </a>
                    </li>



                </ul>
            </li>
            <li class="nav-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/tosayyor'])?>" class="nav-link
                    <?=(Yii::$app->controller->id=='appeal' and Yii::$app->controller->action->id == 'tosayyor')?'active':''?>">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        Сайёр қабул
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


           <?php if(Yii::$app->user->identity->is_village==1){?>
                <li class="nav-item">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/indexhok'])?>" class="nav-link <?=(Yii::$app->controller->id=='appeal' and Yii::$app->controller->action->id == 'indexhok')?'active':''?>">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Вилоят ҳокимлигига юборилган
                        </p>
                    </a>
                </li>
               <?php }?>

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

<style>
    .nav-treeview{
        background: rgb(45, 82, 163) !important;
    }
</style>

