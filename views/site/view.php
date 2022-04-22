<?php

use yii\helpers\Html;
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Муожаат маълумотлари
                </h3>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'appeal_detail',
//                        'appeal_file',
                        [
                            'attribute'=>'appeal_file',
                            'value'=>function($d){
                                if($d->appeal_file){
                                    return "<a href='/upload/{$d->appeal_file}'>Иловани юклаб олиш</a>";
                                }else{
                                    return null;
                                }
                            },
                            'format'=>'raw'
                        ],
//                        'deadtime',

                        'updated',
//                        'boshqa_tashkilot',
                        [
                            'attribute'=>'question_id',
                            'value'=>function($d){
                                if($d->question_id){
                                    $q = $d->question;
                                    return $q->group->code.'-'.$q->code.'.'.$q->name;
                                }
                                return null;
                            },
                        ],
                        [
                            'attribute'=>'appeal_shakl_id',
                            'value'=>function($d){
                                return $d->appealShakl->name;
                            },
                        ],
                        [
                            'attribute'=>'appeal_type_id',
                            'value'=>function($d){
                                return $d->appealType->name;
                            },
                        ],
                        [
                            'label'=>'Қабул қилган ташкилот',
                            'attribute'=>'boshqa_tashkilot',
                            'value'=>function($d){
                                if($d->boshqa_tashkilot){
                                    return $d->boshqaTashkilot->name.'<br>'.$d->boshqa_tashkilot_number.' '.$d->boshqa_tashkilot_date;
                                }else{
                                    return "Бевосита";
                                }
                            }
                        ],
                        [
                            'attribute'=>'company_id',
                            'value'=>function($d){
                                return $d->company->name;
                            },
                        ],

                    ],
                ]) ?>


            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Мурожаатчи ҳақида
                </h3>
            </div>
            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
//                        'id',
                        'person_name',
                        'person_phone',
                        'date_of_birth',
//                        'gender',
                        [
                            'attribute'=>'gender',
                            'value'=>function($d){
                                return Yii::$app->params['gender'][$d->gender];
                            }
                        ],
                        [
                            'attribute'=>'region_id',
                            'value'=>function($d){
                                return $d->region->name;
                            }
                        ],
                        [
                            'attribute'=>'district_id',
                            'value'=>function($d){
                                return $d->district->name;
                            }
                        ],
                        [
                            'attribute'=>'village_id',
                            'value'=>function($d){
                                return @$d->village->name;
                            }
                        ],
                        'address',
                        'email',
                        'businessman',
                    ],
                ]) ?>


            </div>
        </div>
    </div>
</div>




<div class="card">

    <div class="card-body">
        <div class="row">
            <div class="col-md-<?php if($register->status == 0 or $register->status == 1 or $register->status == 2) echo 12; else echo 6?>">
                <h3 class="card-title">
                    Ариза маълумотлари
                </h3>
                <?= DetailView::widget([
                    'model' => $register,
                    'attributes' => [
                        'number',
                        'date',
                        [
                            'label'=>'Юборувчи ташкилот',
                            'attribute'=>'parent_bajaruvchi_id',
                            'value'=>function($d){
                                if($d->parent_bajaruvchi_id){
                                    $res = $d->parent->register;
                                    return $res->company->name.'<br>'.$res->number.'<br>'.$res->date;
                                }else{
                                    return 'Бевосита';
                                }
                            },
                            'format'=>'raw'
                        ],
//                        'deadtime',
                        [
                            'attribute'=>'deadtime',
                            'value'=>function($d){
                                return $d->deadline.' кун '.$d->deadtime;
                            }
                        ],
                        'donetime',
//                        'control_id',
                        [
                            'attribute'=>'control_id',
                            'value'=>function($d){
                                return $d->control->name;
                            }
                        ],
//                        'status',
                        'preview',
//                        'detail',
//                        'file',
//                        'nazorat',
                        [
                            'attribute'=>'nazorat',
                            'value'=>function($d){
                                return Yii::$app->params['nazorat'][$d->nazorat];
                            }
                        ],
//                        'takroriy',
//                        'rahbar_id',
//                        'ijrochi_id',
                        [
                            'attribute'=>'rahbar_id',
                            'value'=>function($d){
                                return @$d->rahbar->name;
                            }
                        ],
                        [
                            'attribute'=>'ijrochi_id',
                            'value'=>function($d){
                                return @$d->ijrochi->name;
                            }
                        ],
                    ],
                ]) ?>

            </div>
            <?php if($register->status > 2 and $register->parent_bajaruvchi_id){?>
                <div class="col-md-6">
                    <h3 class="card-title">
                        Мурожаатнинг жавоби
                    </h3>
                    <?= DetailView::widget([
                        'model' => \app\models\AppealAnswer::find()->where(['parent_id'=>$register->parent_bajaruvchi_id])->orderBy(['created'=>SORT_DESC])->one(),
                        'attributes' => [
                            'number',
                            'date',
                            'preview',
                            'detail',
                            'name',
//                                'file',
                            [
                                'attribute'=>'file',
                                'value'=>function($d){
                                    if($d->file){
                                        return "<a href='/upload/{$d->file}'>Жавоб хатини юклаб олиш</a>";
                                    }else{
                                        return null;
                                    }
                                },
                                'format'=>'raw'
                            ],
//                                'reaply_send',
                            [
                                'attribute'=>'reaply_send',
                                'value'=>function($d){
                                    if($d->reaply_send == 0){
                                        return "Мурожаатчига жавоб хати юборилган";
                                    }else{
                                        return "Мурожаатчига жавоб хати юборилмаган";
                                    }
                                }
                            ],
//                                'status'
                            [
                                'attribute'=>'status',
                                'value'=>function($d){
                                    return $d->status0->name;
                                }
                            ],
                        ],
                    ]) ?>

                </div>
            <?php }elseif($register->status > 2 and !$register->parent_bajaruvchi_id){?>
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' =>$model,
                        'attributes' => [
                            'answer_number',
                            'answer_date',
                            'answer_preview',
                            'answer_detail',
                            'answer_name',
//                                'file',
                            [
                                'attribute'=>'answer_file',
                                'value'=>function($d){
                                    if($d->answer_file){
                                        return "<a href='/upload/{$d->answer_file}'>Жавоб хатини юклаб олиш</a>";
                                    }else{
                                        return null;
                                    }
                                },
                                'format'=>'raw'
                            ],
//                                'reaply_send',
                            [
                                'attribute'=>'answer_reply_send',
                                'value'=>function($d){
                                    if($d->answer_reply_send == 0){
                                        return "Мурожаатчига жавоб хати юборилган";
                                    }else{
                                        return "Мурожаатчига жавоб хати юборилмаган";
                                    }
                                }
                            ],
//                                'status'
                            [
                                'attribute'=>'status',
                                'value'=>function($d){
                                    return $d->status0->name;
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            <?php }?>
        </div>

        <hr>
        <div id="accordion">
            <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/getappeal','id'=>$register->id])?>" class="btn btn-default" id="downappeal"><span class="fa fa-download"></span> Мурожаат масаласини юклаб олиш</a>
            <?php if($task_emp->status>1){?>

            <?php if($register->status != 3 and $model->status != 4){?>
                <div class="dropdown" style="display: inline-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Топшириқ юбориш
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" data-toggle="collapse" href="#success">Ташкилотларга топшириқ бериш</a>
                        <a class="dropdown-item" data-toggle="collapse" href="#">Ҳодимларга топшириқ бериш</a>
                    </div>
                </div>
                <a href="#answer" class="btn btn-success" data-toggle="collapse">Жавоб юбориш</a>

            <?php }?>
            <?php }else{?>
                <a class="btn btn-success" href="<?= Yii::$app->urlManager->createUrl(['/site/ok','id'=>$register->id])?>">Мурожаатни қабул қилиш</a>
            <?php }?>
            <div id="success" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #007bff;" data-parent="#accordion">

                <table class="table table-hover table-bordered datatable_tashkilot">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Ташкилот номи</th>
                        <th>Директор</th>
                        <th>СТИР(ИНН)</th>
                    </tr>
                    </thead>
                    <tbody>



                    </tbody>
                </table>

            </div>

            <div id="taskemp" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #007bff;" data-parent="#accordion">

                <table class="table table-hover table-bordered datatable_emp">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th></th>
                        <th>ФИО</th>
                        <th>Лавозим</th>
                        <th>Бўлим</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $emp = \app\models\User::find()->where(['company_id'=>Yii::$app->user->identity->company_id])->all();
                    $n=0;
                    foreach ($emp as $item): $n++;
                        ?>
                        <tr>
                            <td><?= $n?></td>
                            <td><button class="btn btn-primary taskemp" value="<?= Yii::$app->urlManager->createUrl(['/site/taskemp','id'=>$item->id,'regid'=>$register->id])?>"><span class="fa fa-plus"></span></button></td>
                            <td><?= $item->name?></td>
                            <td><?= $item->lavozim->name?></td>
                            <td><?= $item->bulim->name?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>


            <div id="answer" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #28a745;" data-parent="#accordion">
                <?php if($register->status != 4){?>
                    <?php // if($register->parent_bajaruvchi_id){ echo $this->render('_answerformmy',['model'=>$answer]);}else{echo  $this->render('_closeformmy',['model'=>$model,'register'=>$register,'answer'=>$answer,]);} ?>
                <?php }else{echo "Мурожаатга жавоб юборилган";}?>
            </div>



        </div>

    </div>


</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Топшириқ юборилган ташкилотлар рўйхати
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th></th>
                        <th>Ташкилот номи</th>
                        <th>Топшириқ матни</th>
                        <th>Илова</th>
                        <th>Юборилган сана</th>
                        <th>Ҳолат</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $n=0; foreach ($register->child as $item): $n++?>
                        <tr>
                            <td><?= $n?></td>
                            <td>
                                <?php if($item->status==3 and $item->sender_id==Yii::$app->user->id){?>
                                    <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['/site/showresult','id'=>$item->id])?>"><span class="<?= $item->status0->icon?>"></span></a>
                                <?php }else{?>
                                    <span class="<?= $item->status0->icon?>"></span>
                                <?php }?>
                            </td>

                            <td><?= $item->company->name?></td>
                            <td><?= $item->task?></td>
                            <td><?= $item->letter? "<a href='/upload/{$item->letter}' download>Иловани юклаб олинг</a>" : 'Илова мавжуд эмас'?></td>
                            <td><?= $item->created ?></td>
                            <td><?= $item->status0->name ?></td>
                            <td>
                                <?php if($item->status<2){?>
                                    <a data-method="post" data-confirm="Siz rostdan ham ushbu topshiriqni o`chirmoqchimisiz?" href="<?= Yii::$app->urlManager->createUrl(['/site/deletetask','id'=>$item->id])?>"><span class="fa fa-trash"></span></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Топшириқ юборилган ҳодимлар рўйхати
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th></th>
                        <th>ФИО</th>
                        <th>Топшириқ матни</th>
                        <th>Илова</th>
                        <th>Юборилган сана</th>
                        <th>Юборувчи</th>
                        <th>Ҳолат</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $n=0; foreach ($register->childemp as $item): $n++?>
                        <tr>
                            <td><?= $n?></td>
                            <td><?php if($item->status==3){?>
                                    <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['/appeal/showresult',])?>"><span class="<?= $item->status0->icon?>"></span></a>
                                <?php }else{?>
                                    <span class="<?= $item->status0->icon?>"></span>
                                <?php }?>
                            </td>

                            <td><?= $item->reciever->name ?></td>
                            <td><?= $item->task ?></td>
                            <td><?= $item->letter? "<a href='/upload/{$item->letter}' download>Иловани юклаб олинг</a>" : 'Илова мавжуд эмас'?></td>
                            <td><?= $item->created ?></td>
                            <td><?= $item->sender->name ?></td>
                            <td><?= $item->status0->name ?></td>
                            <td>
                                <?php if($item->status<2){?>
                                    <a data-method="post" data-confirm="Siz rostdan ham ushbu topshiriqni o`chirmoqchimisiz?" href="<?= Yii::$app->urlManager->createUrl(['/appeal/deletetask'])?>"><span class="fa fa-trash"></span></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php endforeach;?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Bu joyi hali yoziladi-->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Ташкилотлар томонидан келган жавоблар рўйхати
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th></th>
                        <th>Ташкилот номи</th>
                        <th>Рақами</th>
                        <th>Ҳужжат номи	</th>
                        <th>Илова</th>
                        <th>Ижрочи</th>
                        <th>Юборилган сана</th>
                        <th>Ҳолат</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $n=0; foreach ($register->childanswermy as $item): $n++?>
                        <tr>
                            <td><?= $n?></td>
                            <td>
                                <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['/site/viewresult','id'=>$item->id])?>"><span class="<?= $item->status0->icon?>"></span></a>
                            </td>

                            <td><?= $item->bajaruvchi->company->name?></td>
                            <td><?= $item->number.'<br>'.$item->date ?></td>
                            <td><?= $item->preview?></td>
                            <td><?= $item->file? "<a href='/upload/{$item->file}' download>Иловани юклаб олинг</a>" : 'Илова мавжуд эмас'?></td>
                            <td><?= $item->name?></td>
                            <td><?= $item->parent->sender->name.'<br>'.$item->created ?></td>
                            <td><?= $item->status0->name ?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="modaltashkilot" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Топшириқ бериш</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ёпиш</button>
            </div>
        </div>

    </div>
</div>



<style>
    .table.table-hover.table-bordered.datatable_tashkilot.dataTable.no-footer{
        width: 100% !important;
    }
</style>


<?php
$url = Yii::$app->urlManager->createUrl(['/site/task','regid'=>$register->id]);
$this->registerJs("
        $(document).ready(function(){
              $('.datatable_tashkilot').DataTable({
                \"processing\": true,
                \"serverSide\": true,

                \"ajax\": {
                    \"url\":\"/get/gettashkilot\",
                    \"type\":\"post\"
                },
                \"columns\": [
                    { \"data\": \"id\" },
                    { \"data\": \"name\" },
                    { \"data\": \"director\" },
                    { \"data\": \"inn\" }
                ],
            });
        })
        
        tashkilotadd = function(id){
            $('#modaltashkilot').modal('show').find('.modal-body').load('{$url}&id='+id);
        }     
        
        $('.datatable_emp').DataTable(); 
        
        $('.taskemp').click(function(){
            var url = this.value;
            $('#modaltashkilot').modal('show').find('.modal-body').load(url);  
        })
        
        
    ")
?>
