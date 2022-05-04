<?php

use app\models\AppealAnswer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Appeal */
/* @var $register app\models\AppealRegister */
/* @var $answer app\models\AppealAnswer */
/* @var $ans app\models\AppealAnswer */

$this->title = $model->person_name;
$this->params['breadcrumbs'][] = ['label' => 'Мурожаатлар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <script>
        var tashkilotadd = function(){}
    </script>
<div class="row">
    <div class="col-md-6">

        <?= $this->render('view/_appeal',['model'=>$model])?>

    </div>
    <div class="col-md-6">

        <?= $this->render('view/_appealer',['model'=>$model])?>

    </div>
</div>




        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-<?php if($register->status == 0 or $register->status == 1 or $register->status == 2) echo 12; else echo 6?>">

                        <?= $this->render('view/_register',['register'=>$register])?>

                    </div>

                    <?php if($register->status > 2 and $register->parent_bajaruvchi_id){?>
                    <div class="col-md-6">

                        <?= $this->render('view/_sended',['register'=>$register])?>

                    </div>
                    <?php }elseif($register->status > 2 and !$register->parent_bajaruvchi_id){?>
                        <div class="col-md-6">

                            <?= $this->render('view/_close',['model'=>$model])?>

                        </div>
                    <?php }?>
                </div>

                <hr>
                <div id="accordion">
                    <div class="dropdown" style="display: inline-block">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Юклаб олинг
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['/appeal/getappeal','id'=>$register->id])?>"  id="downappeal"><span class="fa fa-download"></span> Мурожаат масаласини юклаб олиш</a>
                            <a class="dropdown-item btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['/getappeal/print','id'=>$register->id])?>"  id="downappeal"><span class="fa fa-download"></span> Назорат карточкани юклаб олиш</a>

                        </div>
                    </div>

                    <?php if($register->status != 3 and $model->status !=   4){?>

                        <?= $this->render('view/_buttons',['register'=>$register])?>

                    <?php }?>

                    <?= $this->render('view/_task',['register'=>$register])?>


                    <div id="answer" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #28a745;" data-parent="#accordion">
                        <?php if($register->status != 4){?>
                            <?php if($register->parent_bajaruvchi_id){
                                echo $this->render('view/_answerformmy',['model'=>$answer]);
                            }else{
                                echo  $this->render('view/_closeformmy',['model'=>$model,'register'=>$register,'answer'=>$answer,]);
                            } ?>
                        <?php }else{
                            echo "Мурожаатга жавоб юборилган";
                        }?>
                    </div>



                </div>

            </div>


</div>




<?= $this->render('view/_comp',['register'=>$register])?>
<?= $this->render('view/_compans',['register'=>$register])?>

<?= $this->render('view/_emp',['register'=>$register])?>
    <!--Bu joyi hali yoziladi-->

<?= $this->render('view/_empans',['register'=>$register])?>



<?= $this->render('view/_myanswer',['register'=>$register])?>

<?= $this->render('view/_footer',['register'=>$register])?>