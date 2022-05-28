<?php

namespace app\controllers;

use app\models\Appeal;
use app\models\AppealAnswer;
use app\models\AppealBajaruvchi;
use app\models\AppealComment;
use app\models\AppealRegister;
use app\models\search\AppealBajaruvchiAnsSearch;
use app\models\search\AppealRegisterMyHasSearch;
use app\models\search\AppealRegisterMySearch;
use app\models\search\CompanyMyRegisterSearch;
use app\models\search\CompanyRegisterSearch;
use app\models\TaskEmp;
use app\models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\base\BaseObject;
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

    public function afterAction($action, $result)
    {
        if($action->id !='logout' and $action->id != 'login' and $action->id != 'change' and $action->id != 'profile'){
            if(Yii::$app->user->identity->is_registration){
                return $this->redirect(['/appeal/index']);
            }
        }

        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
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
    public function actionIndex($status=-1)
    {
        $user = Yii::$app->user->identity;

        $searchModel = new AppealRegisterMySearch();
        $searchModel->status = $status;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

    public function actionOk($id){
        $register = AppealRegister::findOne($id);
        $task = TaskEmp::findOne(['register_id'=>$id,'appeal_id'=>$register->appeal_id,'reciever_id'=>Yii::$app->user->id]);

        $task->status = 2;
        $task->save();

        return $this->redirect(['view','id'=>$id]);
    }

    public function actionView($id){

        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $task = TaskEmp::findOne(['register_id'=>$id,'appeal_id'=>$register->appeal_id,'reciever_id'=>Yii::$app->user->id]);
        if($task->status == 0){
            $task->status = 1;
            $task->save();
        }
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
            'task_emp'=>$task
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

    public function actionTask($id,$regid){
        $register = AppealRegister::findOne($regid);
        $model = new AppealBajaruvchi();
        $model->register_id = $register->id;
        $model->appeal_id = $register->appeal_id;
        $model->company_id = $id;
        $model->deadtime = $register->deadtime;
        $model->sender_id = Yii::$app->user->id;
        if(AppealBajaruvchi::find()->where(['company_id'=>$id])->andWhere(['appeal_id'=>$model->appeal_id])->andWhere(['register_id'=>$register->id])->one()){
            return "Ушбу ташкилотга аввал мурожаат юборилган";
        }
        if($model->load(Yii::$app->request->post())){
            $model->sender_id = Yii::$app->user->id;
            $model->upload();
            if($model->save()){
                Yii::$app->session->setFlash('success','Топшириқ юборилди');
            }else{
                Yii::$app->session->setFlash('error','Маълумотлар тўлиқ тўлдирилмаган');
            }
            return $this->redirect(['view','id'=>$regid]);
        }
        return $this->renderAjax('_task',[
            'model'=>$model,
            'id'=>$id,
            'regid'=>$regid,
            'name'=>$model->company->name
        ]);
    }

    public function actionTaskemp($id,$regid){
        $register = AppealRegister::findOne($regid);
        $model = new TaskEmp();
        $model->register_id = $register->id;
        $model->appeal_id = $register->appeal_id;
        $model->sender_id = Yii::$app->user->id;
        $model->reciever_id = $id;
        $model->deadtime = $register->deadtime;
        if(TaskEmp::find()->where(['sender_id'=>$model->sender_id])->andWhere(['reciever_id'=>$id])->andWhere(['appeal_id'=>$model->appeal_id])->andWhere(['register_id'=>$register->id])->one()
            or $register->ijrochi_id == $model->reciever_id or $register->rahbar_id==$model->reciever_id
        ){
            return "Ушбу ҳодимга аввал топшириқ берилган юборилган";
        }
        if($model->load(Yii::$app->request->post())){

            $model->upload();
            if($model->save()){
                Yii::$app->session->setFlash('success','Топшириқ юборилди');
            }else{
                Yii::$app->session->setFlash('error','Маълумотлар тўлдирилмаган');
            }
            return $this->redirect(['view','id'=>$regid]);
        }
        return $this->renderAjax('_task_emp',[
            'model'=>$model,
            'id'=>$id,
            'regid'=>$regid,
            'name'=>User::findOne($model->reciever_id)->name,
        ]);
    }


    public function actionCompanies(){
        $searchModel = new CompanyMyRegisterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->isPost){

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="hisobot.xlsx"');
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $n = 0;
            $sheet->setCellValue('A1', '№');
            $sheet->setCellValue('B1', 'Ташкилот номи');
            $sheet->setCellValue('C1', 'Юборилган мурожаатлар');
            $sheet->setCellValue('D1', 'Қабул қилинмаган');
            $sheet->setCellValue('E1', 'Янги');
            $sheet->setCellValue('F1', 'Жараёнда');
            $sheet->setCellValue('G1', 'Тасдиқланиши кутилмоқда');
            $sheet->setCellValue('H1', 'Бажарилган');
            $sheet->setCellValue('I1', 'Рад этилган');
            $sheet->setCellValue('J1', 'Муддати бузилган');
//            $sheet->setCellValue('J1', 'Муддати бузиб бажарилган');
            foreach ($dataProvider->query->all() as $item){
                $n++;
                $m = $n+1;
                $sheet->setCellValue('A'.$m, $n);
                $sheet->setCellValue('B'.$m, $item->name);
                $sheet->setCellValue('C'.$m, $item->cntall);
                $sheet->setCellValue('D'.$m, $item->cnt0);
                $sheet->setCellValue('E'.$m, $item->cnt1);
                $sheet->setCellValue('F'.$m, $item->cnt2);
                $sheet->setCellValue('G'.$m, $item->cnt3);
                $sheet->setCellValue('H'.$m, $item->cnt4);
                $sheet->setCellValue('I'.$m, $item->cnt5);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save("php://output");

        }
        return $this->render('companies', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);



    }
    public function actionShowresult($id){

        $model = AppealBajaruvchi::findOne($id);
        $register = AppealRegister::findOne($model->register_id);
        $appeal = Appeal::findOne($model->appeal_id);
        $answer = AppealAnswer::find()->where(['parent_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
        $appeal->scenario = 'close';
        $result = AppealRegister::findOne($answer->register_id);
        $com = new AppealComment();
        $com->answer_id = $answer->id;
        $com->status = 5;
        if($com->load(Yii::$app->request->post()) and $com->save()){
            $result->status = 5;
            $model->status = 5;
            $answer->status = 5;
            $result->save(false);
            $model->save(false);
            $answer->save(false);
            return $this->redirect(['view','id'=>$register->id]);
        }


        return $this->render('viewresult',[
            'model'=>$appeal,
            'register'=>$register,
            'bajaruvchi'=>$model,
            'result'=>$result,
            'answer'=>$answer
        ]);

    }
    public function actionAcceptanswer($id){

        $answer = AppealAnswer::findOne($id);
        $model = $answer->parent;
        $register = AppealRegister::findOne($model->register_id);

        $result = AppealRegister::findOne($answer->register_id);

        $result->status = 4;
        $model->status = 4;
        $answer->status = 4;
        closeAppeal($model->appeal_id,$result->id,$result->control_id);
        $result->save(false);
        $model->save(false);
        $answer->save(false);
        return $this->redirect(['view','id'=>$register->id]);

    }

    public function actionClose($id,$ansid){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $model->scenario = "close";
        $answer = AppealAnswer::findOne($ansid);
        $bajaruvchi = $answer->parent;
        $model->answer_file = $answer->file;
        $model->status = 4;
        if($model->load(Yii::$app->request->post()) and $model->save()){
            $register->status = 4;
            $register->donetime = date('Y-m-d');
            $register->control_id = $model->appeal_control_id;
            $register->answer_send = $model->answer_reply_send;
            $register->save();
            $bajaruvchi->status = 4;
            $bajaruvchi->save(false);
            $answer->status = 4;
            $answer->save(false);
            closeAppeal($model->id,$register->id,$register->control_id);
            //return $this->redirect(['acceptanswer','id'=>$ansid]);
        }
        return $this->redirect(['view','id'=>$register->id]);
    }

    public function actionClosemy($id){
        $register = AppealRegister::findOne($id);
        $model = Appeal::findOne($register->appeal_id);
        $model->scenario = "close";

        $model->status = 4;
        if($model->load(Yii::$app->request->post())){
            if($model->answer_file = UploadedFile::getInstance($model,'answer_file')){
                $name = microtime(true).'.'.$model->answer_file->extension;
                $model->answer_file->saveAs(Yii::$app->basePath.'/web/upload/'.$name);
                $model->answer_file = $name;
            }
            if($model->save()){
                $register->status = 4;
                $register->donetime = date('Y-m-d');
                $register->control_id = $model->appeal_control_id;
                $register->answer_send = $model->answer_reply_send;
                $register->save();
                closeAppeal($model->id,$register->id,$register->control_id);
            }

            //return $this->redirect(['acceptanswer','id'=>$answer->id]);
        }
        return $this->redirect(['view','id'=>$register->id]);
    }

    public function actionAnswered($status = 3){
        $searchModel = new AppealBajaruvchiAnsSearch();
        $searchModel->status = $status;
        $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);

        return $this->render('answered', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewresult($id){

        $answer = AppealAnswer::findOne($id);
        $model = AppealBajaruvchi::findOne($answer->parent_id);
        $register = AppealRegister::findOne($model->register_id);
        $appeal = Appeal::findOne($model->appeal_id);

        $result = AppealRegister::findOne($answer->register_id);
        $com = new AppealComment();
        $com->answer_id = $answer->id;
        $com->status = 5;
        if($com->load(Yii::$app->request->post()) and $com->save()){
            $result->status = 5;
            $model->status = 5;
            $answer->status = 5;
            $result->save(false);
            $model->save(false);
            $answer->save(false);
            return $this->redirect(['view','id'=>$register->id]);
        }
        $appeal->scenario = 'close';

        return $this->render('viewresult',[
            'model'=>$appeal,
            'register'=>$register,
            'bajaruvchi'=>$model,
            'result'=>$result,
            'answer'=>$answer
        ]);

    }


}
