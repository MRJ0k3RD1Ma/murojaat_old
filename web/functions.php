<?php

use app\models\Person;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
function GenerateRandomUnicalString($model){
    $code = Yii::$app->security->generateRandomString(12);
    while($model->where(['code'=>$code])->count()!=0){

        $code = Yii::$app->security->generateRandomString(12);

    }
    return $code;
}
function generateVillages($villages){
    $n=0;
    $val = "[";
    foreach ($villages as $key=>$item){
        if($item == 1){
            $n++;
            if($n == 1){
                $val .= "\"$key\"";
            }else{
                $val .= ",\"$key\"";
            }
        }

    }
    $val .= "]";
    return $val;
}
function jsonToString($json)
{
    $result='';
    $data=json_decode($json);
	//return json_encode($json);
    $first=true;
    $used=[];
    $i=0;
    if($data==null){
        return "";
    }
    foreach ($data as $item) {

        if($type=\app\models\Type::findOne($item)){
            if(!in_array($type->group_type_id,$used)){
                if($first){
                    $result.='<b>'.$type->groupType->name.':</b> '.$type->name;
                    $first=false;
                }
                else{
                    $result.='<br><b>'.$type->groupType->name.':</b> '.$type->name;
                }
                $used[$i++]=$type->group_type_id;
            }
            else{
                $result.=', '.$type->name;
            }
			//$result.=$type->name;
        }

    }
    return $result;
}
function jsonToStringg($json)
{
    $result='';
    if($data=json_decode($json,true)){

        $first=true;
        $used=[];
        $i=0;
        foreach ($data as $item) {
            if($type=\app\models\Type::findOne($item[0])){
                if(!in_array($type->group_type_id,$used)){
                    if($first){
                        $result.=$type->groupType->name.': '.$type->name;
                        $first=false;
                    }
                    else{
                        $result.=';<br> '.$type->groupType->name.': '.$type->name;
                    }
                    $used[$i++]=$type->group_type_id;
                }
                else{
                    $result.=', '.$type->name;
                }
            }

        }

    }
    return $result;
}

function calculateBall($json){
    $ball=0;
    $data=[];
    $used = [];
    foreach (\app\models\GroupType::find()->all() as $item) {
        $used[$item->id]=false;
    }
    try {
        $data=json_decode($json);
    }
    catch (\yii\db\Exception $ex){}
//    debug($json);
//    exit();


    try {
        foreach ($data as $item) {
            $type=\app\models\Type::findOne($item);
            $grTy= $type->group_type_id;
            if(!$used[$grTy]){
                $used[$grTy]=true;
            }
            $ball+=$type->ball;
        }
    }
    catch (Exception $ex){
        debug($ex);
        debug($data);
        debug($json);
    }

    foreach (\app\models\GroupType::find()->all() as $item) {
        if(!$used[$item->id]){
            $used[$item->id]=true;
            $ball+=100;
            if($item->id==15)
                $ball-=200;
        }
//        debug();
    }
//    debug($ball);
//    exit();
    return $ball;
}
function regenerateTypes($json){
    $a = [];
    if ($json) {
        $a = json_decode($json, true);
    }

    $i=0;
    $result=[];
    foreach (\app\models\Type::find()->all() as $item) {
        $result[$i]=0;
        if (in_array($item->id, $a)) {
            $result[$i]=$item->id;
        }
        $i++;
    }
    return $result;
}
function generateTypes($types_int){
    $type_ids = [];
    if ($types_int) {
        foreach ($types_int as $item) {
            if ($item){
                array_push($type_ids, "{$item}");
            }
        }
    }

    return json_encode($type_ids);

}
function exportToExcel($label=null,$data=null){
//debug($data);
//exit();
	ini_set('memory_limit', '2048M');
	ini_set('max_execution_time ', '200');
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
//    $sheet->setCellValue('A1', 'Hello World !');
    $sheet->fromArray($data,null,'B2');
    $sheet->fromArray($label,null,'A1');
//    debug(sizeof($data));
//exit();
$tr=[];
$tr[0][0]='Т\р';
for ($i=1;$i<=sizeof($data);$i++)
    $tr[$i][0]=$i;
    $sheet->fromArray($tr,'A3');
//debug($tr);
//exit();
    $writer = new Xlsx($spreadsheet);
    $writer->save('filename.xlsx');
    Yii::$app->response->sendFile('filename.xlsx');
//    debug($writer);
}

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}
function getColor($status){
    $color = [
        1=>'bg-warning',
        0=>'bg-info',
        2=>'bg-success',
        3=>'bg-danger'
    ];
    return $color[$status];
}
// type some code
function closeAppeal($id,$reg_id,$c_id){
    $reg = \app\models\AppealRegister::find()->where(['appeal_id'=>$id])->andWhere(['>=','id',$reg_id])->all();
    foreach ($reg as $item){
        $item->status = 4;
        $item->control_id = $c_id;
        $item->donetime = date('Y-m-d');
        if($baj = $item->parent){
            $baj->status = 4;
            $baj->save();
        }

        $item->save();
    }
}
?>