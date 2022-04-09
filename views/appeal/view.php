<?php

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
                            }
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
                                return $d->village->name;
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
            <div class="card-header">
                <h3 class="card-title">
                    Ариза маълумотлари
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-<?php if($register->status == 0 or $register->status == 1 or $register->status == 2) echo 12; else echo 6?>">
                <?= DetailView::widget([
                    'model' => $register,
                    'attributes' => [
                        'number',
                        'date',
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
                </div>

                <hr>
                <div id="accordion">

                    <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/getappeal','id'=>$register->id])?>" class="btn btn-default" id="downappeal"><span class="fa fa-download"></span> Мурожаат масаласини юклаб олиш</a>

                    <a href="#success" class="btn btn-success" data-toggle="collapse">Ташкилотларга топшириқ бериш</a>

                    <div class="dropdown" style="float: right">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Маълумотларни янгилаш
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Мурожаат маълумотлари</a>
                            <a class="dropdown-item" href="#">Мурожаатчи маълумотлари</a>
                        </div>
                    </div>



                    <div id="success" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #28a745;" data-parent="#accordion">

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
$url = Yii::$app->urlManager->createUrl(['/appeal/task','regid'=>$register->id]);
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
        
    ")
?>