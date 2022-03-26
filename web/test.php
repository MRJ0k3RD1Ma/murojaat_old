<?php
$a = 10;
$s = (4-pow(cos($a),2))/pow(sin(cos($a)),2)*exp(1);

$f = pow(cos(asin(M_PI*$a)-tan($s)),2);

if($s>=6){
    $g = ($s - 2*$f)/($s*$s + $f*$f);
}else{
    $g = ($s - 2*$f)/($s*$s - $f*$f);
}
echo $g;
