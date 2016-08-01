<?php
header("content-type:text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
//$addUrl="http://192.168.5.11:8681/orderApi/adminDiv/list?type=PROVINCE";//测试地址
$addUrl="http://api.htche.com/orderApi/adminDiv/list?type=PROVINCE"; //线上地址
$ch = curl_init(); //初始化curl
curl_setopt($ch,CURLOPT_URL,$addUrl );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_TIMEOUT,10*50000);//设置延迟时间
$resphone= curl_exec($ch);
curl_close($ch); //关闭curl链接
$phArr=json_decode($resphone,true);
if($phArr['code']==100){
	$str='<option value="请选择省">请选择省</option>';
		foreach($phArr['message'] as $vas){
		 	$str.='<option value="'.$vas['code'].'|'.$vas['name'].'">'.$vas["name"].'</option>';
		}
}
	echo $str;
