<?php

namespace app\controllers;

use app\models\Appeal;
use app\models\AppealAnswer;
use app\models\AppealBajaruvchi;
use app\models\AppealComment;
use app\models\AppealRegister;
use app\models\Company;
use app\models\search\AppealRegisterMyClosedSearch;
use app\models\search\AppealRegisterMyDeadSearch;
use app\models\search\AppealRegisterMyHasSearch;
use app\models\search\AppealRegisterMyRunningSearch;
use app\models\search\AppealRegisterMySearch;
use app\models\User;
use app\models\Village;
use Codeception\Step\Comment;
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

class SiteController extends Controller
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
                        'actions' => ['login','error','start'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],

                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest and Yii::$app->user->identity->active == 0 and Yii::$app->controller->action->id != 'change'){
            return $this->redirect(['change']);
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($type = null, $filter = null)
    {
        $user = Yii::$app->user->identity;

        if($type=='closed'){
            $searchModel = new AppealRegisterMyClosedSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif($type == 'running'){
            $searchModel = new AppealRegisterMyRunningSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }elseif($type == 'dead'){
            $searchModel = new AppealRegisterMyDeadSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }else{
            $searchModel = new AppealRegisterMySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        if($filter == 'hasanswer'){
            // javob fayllari bilan kelgan murojaatlar ro'yhati
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $oldtash = $register->tashkilot;
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

    public function actionView($id){

        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $answer = new AppealAnswer();
        $answer->appeal_id = $register->appeal_id;
        $answer->register_id = $register->id;
        $answer->date = date('Y-m-d');
        $answer->tarqatma_date = date('Y-m-d');
        $answer->bajaruvchi_id = Yii::$app->user->id;

        return $this->render('view',[
            'model'=>$model,
            'register'=>$register,
            'answer'=>$answer,
        ]);
    }

    public function actionAnswerlist(){

        $searchModel = new AppealRegisterMyHasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('answerlist',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddcompany($id){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        
        return $this->render('addcompany',[
            'model'=>$model,
            'register'=>$register,
        ]);
    }


    public function actionAnswer($id){
        $register = AppealRegister::findOne($id);
        $model = new AppealAnswer();
        $model->appeal_id = $register->appeal_id;
        $model->register_id = $register->id;
        $model->date = date('Y-m-d');
        $model->bajaruvchi_id = Yii::$app->user->id;
        $model->tarqatma_date = date('Y-m-d');
        $model->status_boshqa = 0;
        $model->status = 1;
        if($model->load(Yii::$app->request->post())){
            if($model->file = UploadedFile::getInstance($model,'file')){
                $name = microtime(true).'.'.$model->file->extension;
                $model->file->saveAs(Yii::$app->basePath.'/web/upload/'.$name);
                $model->file = $name;
            }
			if($model->bajaruvchi_id == $register->rahbar_id){
				$model->status = 2;
				$register = $model->register;
                $appeal = $model->appeal;
                $register->status = 2;
                $register->detail = $model->detail;
                $register->file = $model->file;
                $register->answer_send = $model->reaply_send;
                $register->save();
				closeAppeal($register->appeal_id,$register->id);
			}
            if($model->save()){
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
        exit;
    }

    public function actionAccept($id,$reg_id = 0){

        $model = AppealAnswer::findOne($id);
        $comment = new AppealComment();
        $comment->answer_id = $id;
        $ooppoo = $reg_id;
        if($comment->load(Yii::$app->request->post())) {

            $comment->save();
            if($reg_id == 0){
                $model->status = $comment->status;
                if($model->status == 2){
                    $reg = AppealRegister::findOne($model->register_id);
                    $ans = isset($reg->user_answer) ? json_decode($reg->user_answer,true) : [];
                    if(!in_array($model->bajaruvchi_id, $ans)){
                        $ans[] = $model->bajaruvchi_id;
                        $reg->user_answer = json_encode($ans);
                        $reg->save();
                    }
                }
            }else{
                $model->status_boshqa = $comment->status;
                if($model->status_boshqa == 3){
                    $register = $model->register;
                    $register->status = 3;
                    $register->control_id = 1;
                    $register->save();
                }else{
                    $reg = $model->register;
                    $ans = isset($reg->tashkilot_answer) ? json_decode($reg->tashkilot_answer,true) : [];
                    if(!in_array($model->bajaruvchi->company_id, $ans)){
                        $ans[] = $model->bajaruvchi->company_id;
                        $reg->tashkilot_answer = json_encode($ans);
                        $reg->save();
                    }
                    $reg->donetime = date('Y-m-d');
                    $reg->status = 2;
                    $reg->save();
                }
            }
            $model->save();

            $user = Yii::$app->user->identity;

            if($user->id == $model->register->rahbar_id and $model->status == 2 and ($model->bajaruvchi_id == $model->register->ijrochi_id)){

                $register = $model->register;
                $appeal = $model->appeal;
                $register->status = 2;
                $register->detail = $model->detail;
                $register->file = $model->file;
                $register->answer_send = $model->reaply_send;
                $register->save();
                // testlash kerak
                closeAppeal($appeal->id,$register->id);

            }

        }

        if($ooppoo != 0){
            return $this->redirect(['view','id'=>$ooppoo]);
        }
        return $this->redirect(['view','id'=>$model->register_id]);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout='login.php';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    public function actionChange(){
        $model = User::findOne(Yii::$app->user->id);

        $this->layout='login.php';

        if ($model->load(Yii::$app->request->post())) {
            $model->encrypt();
            $model->active = 1;
            $model->save();
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('change', [
            'model' => $model,
        ]);
    }


}
