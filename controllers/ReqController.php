<?php

namespace app\controllers;

use app\models\search\RequestSearch;
use yii\web\Controller;
use Yii;


class RequestController extends Controller
{
    public function actionIndex($type){
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        ]);
    }
}
