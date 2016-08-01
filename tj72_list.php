<?php
header('Content-type:text/json;charset=UTF-8');
header('Access-Control-Allow-Origin: *');
include 'sqlin.php';
include 'config627.php';
    $cname="别克GL8";
    $mysql="SELECT distinct u_tel,u_name FROM tj_step2 WHERE car_name='$cname'";
    $result=mysql_query($mysql);
    $data=array();
while($row = mysql_fetch_array($result))
  {
    array_push($data,array("name"=>mb_substr($row['u_name'],0,1,'utf-8')."**","tel"=>mb_substr($row['u_tel'],0,3,'utf-8')."****".mb_substr($row['u_tel'],7,4,'utf-8')));
  
  }
echo json_encode($data);
?>