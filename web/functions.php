<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
function exportToExcel($label=null,$data=null){

	ini_set('memory_limit', '2048M');
	ini_set('max_execution_time ', '200');
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray($data,null,'B2');
    $sheet->fromArray($label,null,'A1');

$tr=[];
$tr[0][0]='Т\р';
for ($i=1;$i<=sizeof($data);$i++)
    $tr[$i][0]=$i;
    $sheet->fromArray($tr,'A3');

    $writer = new Xlsx($spreadsheet);
    $writer->save('filename.xlsx');
    Yii::$app->response->sendFile('filename.xlsx');
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