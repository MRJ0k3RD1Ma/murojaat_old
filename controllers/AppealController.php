<?php

namespace app\controllers;

use app\models\Appeal;
use app\models\AppealAnswer;
use app\models\AppealBajaruvchi;
use app\models\AppealRegister;
use app\models\Company;
use app\models\search\AppealBajaruvchiComSearch;
use app\models\search\AppealBajaruvchiSearch;
use app\models\search\AppealRegisterClosedSearch;
use app\models\search\AppealRegisterComSearch;
use app\models\search\AppealRegisterDeadSearch;
use app\models\search\AppealRegisterHasSearch;
use app\models\search\AppealRegisterRunningSearch;
use app\models\search\AppealRegisterSearch;
use app\models\User;
use app\models\Village;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class AppealController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],

                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($type = null)
    {
        $user = Yii::$app->user->identity;
        if($type=='closed'){
            $searchModel = new AppealRegisterClosedSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif($type == 'running'){
            $searchModel = new AppealRegisterRunningSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif($type == 'dead'){
            $searchModel = new AppealRegisterDeadSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }else{
            $searchModel = new AppealRegisterSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionComplist($id){
        if($com = Company::findOne($id)){
            $searchModel = new AppealBajaruvchiComSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('combaj', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'company'=>$com
            ]);
        }else{
            return $this->redirect(['index']);
        }
    }


    public function actionAnswerlist(){

        $searchModel = new AppealRegisterHasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('answerlist',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id,$ans = 0){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $answer = null;
        if($ans == 0){
            $answer = new AppealAnswer();
            $answer->appeal_id = $model->id;
            $answer->register_id = $register->id;
        }else{
            $answer = AppealAnswer::findOne($ans);
            $answer->name = $answer->bajaruvchi->name;
        }
        return $this->render('view',[
            'model'=>$model,
            'register'=>$register,
            'answer'=>$answer,
        ]);
    }
    public function actionAnswer($id,$ans = 0){
        $register = AppealRegister::findOne($id);
        $model = null;
        $file = null;
        if($ans == 0){
            $model = new AppealAnswer();
            $model->appeal_id = $register->appeal_id;
            $model->register_id = $register->id;
            $model->date = date('Y-m-d');
            $model->bajaruvchi_id = Yii::$app->user->id;
            $model->tarqatma_date = date('Y-m-d');
        }else{
            $model = AppealAnswer::findOne($ans);
            $file = $model->file;
        }

        if($model->load(Yii::$app->request->post())){
            if($model->file = UploadedFile::getInstance($model,'file')){
                $name = microtime(true).'.'.$model->file->extension;
                $model->file->saveAs(Yii::$app->basePath.'/web/upload/'.$name);
                $model->file = $name;
            }else{
                $model->file = $file;
            }

            $model->status_boshqa = 1;
            $model->status = 2;

            if($model->save()){
                $register->status = 2;
                $register->detail = $model->detail;
                $register->file = $model->file;
                $register->answer_send = $model->reaply_send;
                $register->control_id = $model->n_olish;
                $register->save();
                if($register->parent_bajaruvchi_id != null){
                    $appeal = $register->appeal;
                    $appeal->status = 1;
                    $appeal->appeal_control_id = $model->n_olish;
                    $appeal->save();
                }else{
                    closeAppeal($register->appeal_id,$register->id);
                    $appeal = $register->appeal;
                    $appeal->answer_name = $model->name;
                    $appeal->answer_file = $model->file;
                    $appeal->answer_preview = $model->preview;
                    $appeal->answer_detail = $model->detail;
                    $appeal->answer_reply_send = $model->reaply_send;
                    $appeal->answer_number = $model->number;
                    $appeal->answer_date = $model->date;
                    $appeal->status = 2;
                    if($appeal->save()){
                        $model->delete();
                    }else{
                        echo "<pre>";
                        var_dump($appeal);
                        exit;
                    }
                }
                return $this->redirect(['view','id'=>$register->id]);
            }else{
                echo "<pre>";
                var_dump($model);
                exit;
            }
        }
        $this->renderAjax('_answerform',[
            'model'=>$model
        ]);
    }

    public function actionCreate(){
        $model = new Appeal();

        $register = new AppealRegister();
		$register->preview = "Мурожаатни кўриб чиқиб, кўтарилган масалани ўрнатилган тартибда ҳал қилиб, натижаси ҳақида муаллифга жавоб хати тайёрлансин.";
        $register->deadline = 15;
        $register->date = date('Y-m-d');
        $register->company_id = Yii::$app->user->identity->company_id;
        $register->deadtime = date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days'));;
        $model->region_id = Yii::$app->user->identity->company->region_id;
        $model->count_applicant = 1;
        $model->count_list = 1;
		$model->appeal_control_id = 1;
        //appeal_file file upload
        //users array to json
        $model->district_id = Yii::$app->user->identity->company->district_id;

        if($model->load(Yii::$app->request->post()) and $register->load(Yii::$app->request->post())){
            $model->deadtime = $register->deadtime;
            if($model->appeal_file = UploadedFile::getInstance($model,'appeal_file')){
                $name = microtime(true).'.'.$model->appeal_file->extension;
                $model->appeal_file_extension = $model->appeal_file->extension;
                $model->appeal_file->saveAs(Yii::$app->basePath.'/web/upload/'.$name);
                $model->appeal_file = $name;
            }
            if($model->isbusinessman == 0){
                $model->businessman = null;
            }
            if($model->boshqa_tashkilot != 1){
                $model->boshqa_tashkilot_date = null;
                $model->boshqa_tashkilot_group_id = null;
                $model->boshqa_tashkilot_id = null;
                $model->boshqa_tashkilot_number = null;
            }
            $model->upload();

            if($model->save()){

                $register->appeal_id = $model->id;
                $tashkilot = isset($register->tashkilot) ? $register->tashkilot : [];
                $register->control_id = 1;
                $register->users = json_encode($register->users);
                $register->tashkilot = json_encode($register->tashkilot);
                try {
                    $register->save();
                    if(count($tashkilot)>0){
                        foreach ($tashkilot as $user) {
                            $baj = new AppealBajaruvchi();
                            $baj->company_id = $user;
                            $baj->appeal_id = $model->id;
                            $baj->register_id = $register->id;
                            $baj->deadtime = $register->deadtime;
                            $baj->deadline = $register->deadline;
                            $baj->letter = $model->letter;
                            $baj->save();
                           
                            $baj = null;
                        }
                    }
                    return $this->redirect(['view','id'=>$register->id]);
                }catch (\Exception $e){
					$bar = AppealBajaruvchi::find()->where(['appeal_id'=>$model->id])->all();
					foreach ($bar as $it){$it->delete();}
                    $model->delete();
                }

            }else{
                echo "<pre>";
                var_dump($model);
                exit;
            }

        }
        return $this->render('create',[
            'model'=>$model,
            'register'=>$register
        ]);
    }

    public function actionRegform($id){
        $model = new AppealRegister();
        $baj = AppealBajaruvchi::findOne($id);
        $model->appeal_id = $baj->appeal_id;
        $model->parent_bajaruvchi_id = $baj->id;
        $model->company_id = $baj->company_id;
        $model->deadline = $baj->deadline;
        $model->deadtime = $baj->deadtime;
        if($model->load(Yii::$app->request->post())){

            if($model->save()){
                $baj->status = 1;
                $baj->save();
                $app = $model->appeal;
                $app->appeal_control_id = 1;
                $app->save();
                return $this->redirect(['view','id'=>$model->id]);
            }else{
                echo "<pre>";
                var_dump($model);
                exit;
            }
        }
        return $this->renderAjax('_register',[
            'model'=>$model
        ]);
    }

    public function actionNotregister(){

        $searchModel = new AppealBajaruvchiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('notregister', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $oldtashkilot = isset($register->tashkilot) ? json_decode($register->tashkilot,true) : [] ;
        if($register->load(Yii::$app->request->post())){
// tashkilotlardan murojaatni o'chirish ko'nasinda yo'qini o'chirish tozalarini qo'shish
            $register->appeal_id = $model->id;
            $tashkilot = isset($register->tashkilot) ? $register->tashkilot : [];
            $register->control_id = 1;
            $register->users = isset($register->users) ? json_encode($register->users) : null;
            $register->tashkilot = isset($register->tashkilot) ? json_encode($register->tashkilot) : null;
            $register->upload();

            if($register->save()){

                if(is_array($tashkilot) and count($tashkilot) == 1 and $tashkilot[0] == "-1"){
                    $tashkilot = [];
                    $register->tashkilot = null;
                }


                if($oldtashkilot and isset($oldtashkilot) and is_array($oldtashkilot)){
                    foreach ($oldtashkilot as $item){
                        if($tashkilot and  isset($tashkilot) and is_array($tashkilot) and !in_array($item, $tashkilot)){
                            if($baj = AppealBajaruvchi::find()->where(['register_id'=>$register->id])->andWhere(['appeal_id'=>$register->appeal_id])->andWhere(['company_id'=>$item])->one()){

                                if($r = AppealRegister::findOne(['parent_bajaruvchi_id'=>$baj->id])){
                                    $r->delete();
                                }
                                $baj->delete();

                            }
                        }
                    }
                }

                if(is_array($tashkilot)) {

                    foreach ($tashkilot as $user) {
                        if ($oldtashkilot and  isset($oldtashkilot) and is_array($oldtashkilot) and !in_array($user, $oldtashkilot)) {
                            $baj = new AppealBajaruvchi();
                            $baj->company_id = $user;
                            $baj->appeal_id = $model->id;
                            $baj->register_id = $register->id;
                            $baj->deadtime = $register->deadtime;
                            $baj->deadline = $register->deadline;
                            $baj->letter = $register->letter;
                            $baj->save();
                            $baj = null;
                        } else {

                            if($baj = AppealBajaruvchi::find()->where(['company_id'=>$user])->andWhere(['register_id'=>$id])->andWhere(['appeal_id'=>$register->appeal_id])->one()){
                                $baj->company_id = $user;
                                $baj->appeal_id = $model->id;
                                $baj->register_id = $register->id;
                                $baj->deadtime = $register->deadtime;
                                $baj->deadline = $register->deadline;
                                $baj->letter = $register->letter;
                                $baj->save();

                                $baj = null;
                            }else{
                                $baj = new AppealBajaruvchi();
                                $baj->company_id = $user;
                                $baj->appeal_id = $model->id;
                                $baj->register_id = $register->id;
                                $baj->deadtime = $register->deadtime;
                                $baj->deadline = $register->deadline;
                                $baj->letter = $register->letter;
                                $baj->save();
                                $baj = null;
                            }



                        }

                    }
                }





                return $this->redirect(['view','id'=>$register->id]);
            }else{

                echo "<pre>";
                var_dump($register);
                exit;
            }
        }

        return $this->render('_update',[
            'model'=>$model,
            'register'=>$register
        ]);
    }

    public function actionQuest($id,$quest){
        if($model = AppealRegister::findOne($id)){
            $appeal = $model->appeal;
            $appeal->question_id = $quest;
            if($appeal->save()){
                Yii::$app->session->setFlash('success','Савол ўзгартирилди');
            }else{
                Yii::$app->session->setFlash('error','Саволни сақлашда хатолик');
            }
        }else{
            Yii::$app->session->setFlash('error','Маълумотлар етарли эмас');
        }
    }
}
