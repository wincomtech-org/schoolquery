<?php
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
echo 'dd';
$sql = "delete from " . $dou->table('score') . " where name='高考'";
$dou->query($sql);


$array=array('北京','上海','天津','重慶','河北',
   '山西', '內蒙古','遼寧','吉林','黑龍江',
    '江蘇','浙江','安徽','福建','江西','山東',
    '河南','湖北','湖南','廣東','廣西','海南',
    '四川','貴州','雲南','西藏','陝西','甘肅',
    '青海','寧夏','新疆','香港','澳門','台灣'
    
);
 $i=6;
 $time=time();
foreach($array as $v){
        
        $sql = "INSERT INTO " . $dou->table('score') . " (id,name,content,sort,addtime)" . 
            " VALUES ($i,'高考', '$v',$i,'$time')";
        $dou->query($sql);
        $i++;
     }
echo 'dd';
exit;