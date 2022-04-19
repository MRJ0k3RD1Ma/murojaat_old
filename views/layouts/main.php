<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\bootstrap\Progress;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<?php $this->beginBody() ?>
<script>
    function filter() {

    }
    function close_modal() {

    }
    function person_export() {

    }
    function close_export() {

    }

</script>

<div class="wrapper">


    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block align-center">
                <a href="/" class="nav-link">
                    "E-MUROJAAT.UZ" Ахборот тизими
                </a>
            </li>

        </ul>


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <?php if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_registration==1){?>
                <li class="nav-item"><a href="<?= Yii::$app->urlManager->createUrl(['/appeal/create'])?>" class="btn btn-primary"><span class="fa fa-plus"></span> Янги мурожаат</a></li>

                <li class="nav-item"><a href="<?= Yii::$app->urlManager->createUrl(['/appeal/notregister'])?>" class="btn btn-success"><span class="fa fa-registered"></span> Рўйхатга олинмаган</a></li>
            <?php }?>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-envelope"></i>
                    <span class="badge badge-warning navbar-badge">


                        <?php $cnt_ans = \app\models\AppealAnswer::find()->select(['appeal_answer.*'])
                            ->innerJoin('appeal_register','appeal_register.id = appeal_answer.register_id')
                            ->where('appeal_register.parent_bajaruvchi_id IN (SELECT ar.id FROM appeal_register ar WHERE ar.company_id='.Yii::$app->user->identity->company_id.')')
                            ->andWhere(['appeal_answer.status'=>0])
                            ->orderBy(['created'=>SORT_DESC])->count('appeal_answer.id'); echo $cnt_ans?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Жавоби келган</span>
                    <div class="dropdown-divider"></div>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/answered'])?>" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> <?= $cnt_ans ?> та жавоб келган
                    </a>

                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-envelope"></i>
                    <span class="badge badge-warning navbar-badge">


                        <?php $cnt_ans = \app\models\AppealBajaruvchi::find()
                            ->where('register_id in (select id from appeal_register where company_id='.Yii::$app->user->identity->company_id.')')
                            ->andWhere(['status'=>3])->count('id');

                        echo $cnt_ans?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Жавоби келган</span>
                    <div class="dropdown-divider"></div>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/answered'])?>" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> <?= $cnt_ans ?> та жавоб келган
                    </a>

                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge"><?php $cnt = \app\models\AppealBajaruvchi::find()->where(['status'=>0])->andWhere(['company_id'=>Yii::$app->user->identity->company_id])->count('id'); echo $cnt?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Мурожаатлар</span>
                    <div class="dropdown-divider"></div>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/notregister'])?>" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> <?= $cnt ?> та рўйхатга олинмаган
                    </a>

                </div>
            </li>

            <li class="nav-item">
                <?=Html::a('<i class="fa fa-door-open"></i> Chiqish',['/site/logout'],[
                    'class'=>'nav-link',
                    'data' => [
                        'confirm' => Yii::t('app', 'Haqiqatdan ham dasturdan chiqmoqchimisiz?'),
                        'method' => 'post',
                    ],
                ])?>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary">
        <!-- Brand Logo -->
        <div align="center">
            <br />
            <img src="/logo_2.png" style="max-width: 80%;width: 100px;" alt="logo" />
            <br />
            <div style="padding: 10px;" class="brand-text text-white">
                "E-MUROJAAT.UZ" автоматлаштирилган мурожаатлар мониторинги ахборот тизими
            </div>
        </div>
        <hr />

        <!-- Sidebar -->
        <div class="sidebar">

            <?=$this->render('_menu')?>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?=$this->render('_brandcrubs')?>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?=$content?>
            </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer hidden-sm hidden-xs" style="position: relative">
        <strong>&copy; "E-MUROJAAT.UZ" ахборот тизими <a href="http://raqamli.uz">"Рақамли иқтисодиётни ривожлантириш" МЧЖ</a> томонидан ишлаб чиқарилган.</strong>
        Барча ҳуқуқлар ҳимояланган.

    </footer>
</div>

    <div class="telegram-popup" align="center">

        <p style="margin-bottom:-5px;">Тел: +998(62)223-18-78</p>
        <!-- Telegram icon. I like to use font-awesome but dont want to effect your imports too much.-->
        <a href="https://t.me/joinchat/DyZIXpPeovI5NmVi" target="_blank" class="telegram-button-link"><div class="telegram-button">
                <p>
                    <i class="fab fa-telegram-plane"></i>
                    Телеграм гуруҳга аъзо бўлинг
                </p>

            </div></a>

    </div>
    <style>
        body.sidebar-collapse .telegram-popup{
            display: none !important;
        }
        /*Body css here just to demonstrate scrolling*/
        .telegram-popup{
            width: 291px;
            position: fixed;
            bottom: 10px;
            left: -22px;
            z-index: 2000;
            /*round corners*/
            border-radius: 10px;
            /*cool option borders.*/

            /*animate. hide on load*/
            display: none;
        }

        /*text stuff*/
        .telegram-popup p{
            color: #fff;
            padding: 4px;
        }

        .telegram-button{
            background-color: #1682FB;
            width: 80%;
            border-radius: 25px;
        }

        .telegram-button:hover{
            background-color: #1080F5;
        }

        .telegram-button p{
            color: #FFFFFF;
            font-size: 15px;
            /*padding makes the link like a bubble*/
            padding: 10px;
        }

        .telegram-button-link:link{
            text-decoration: none;
        }

        .swal2-checkbox input{
            display: none;
        }

    </style>
    <?php
    $this->registerJs("
        //Animation. 
        $(document).ready(function(){
            $(\".telegram-popup\").delay(3000).show(0);
        });
    ")
    ?>
<?php
    $this->registerJs("
    $(document).ready(function() {
        $('.js-select2').select2();
    });
")
?>



<?php
if(Yii::$app->session->hasFlash('error')){
    $txt = Yii::$app->session->getFlash('error');
    $xato = 'Хатолик';
    $this->registerJs("
        $(document).ready(function(){
            Swal.fire({
              icon: 'error',
              title: \"{$xato}\",
              text: \"{$txt}\"
            })
        })
    ");

}
if(Yii::$app->session->hasFlash('success')){
    $txt = Yii::$app->session->getFlash('success');
    $xato = 'Muvvofaqiyatli';
    $this->registerJs("
        $(document).ready(function(){
            Swal.fire({
              icon: 'success',
              title: \"{$xato}\",
              text: \"{$txt}\"
            })
        })
    ");

}
?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
