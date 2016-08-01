<?php
header('Access-Control-Allow-Origin: *');
if(isset($_POST['pcid'])){
//$addUrl="http://192.168.5.11:8681/orderApi/adminDiv/list?type=CITY&parentCode=".$_POST['pcid'];//测试
$addUrl="http://api.htche.com/orderApi/adminDiv/list?type=CITY&parentCode=".$_POST['pcid']; //线上

	$ch = curl_init(); //初始化curl
	curl_setopt($ch,CURLOPT_URL,$addUrl );
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT,10*50000);//设置延迟时间
	$resphone= curl_exec($ch);
	curl_close($ch); //关闭curl链接
	$cArr=json_decode($resphone,true);
	if($cArr['code']==100){
	$str='<option value="请选择市">请选择市</option>';
		foreach($cArr['message'] as $vas){
		 $str.='<option value="'.$vas['code'].'|'.$vas['name'].'" money="0">'.$vas["name"].'</option>';
		}
	}
	echo $str;
}
?>