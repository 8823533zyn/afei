<?php
$pdo = new PDO("mysql:host=localhost;dbname=cars_db", "afei", "8823533");
$pdo->query('set names utf8;');

include('/simple_html_dom-master/simple_html_dom.php');
$html = new simple_html_dom();
$text = file_get_contents('http://www.autohome.com.cn/suv/');
$text = iconv("GB2312", "UTF-8//IGNORE", $text);
$html->load($text);


$a = $html->find('.tab-content-item')[0];
$a = $a->find('.uibox');
echo count($a);
//echo $a[0]->outertext;
foreach ($a as $value) {
    //echo $value->innertext;
    echo $value->first_child()->plaintext;
    $englishName = $value->first_child()->plaintext;
    echo "|";
    $cars = $value->find('dl');
    foreach ($cars as $car) {
        echo $car->find('dt div a')[0]->innertext;
        $BRAND = $car->find('dt div a')[0]->innertext;
        //插入品牌
        $query = "INSERT INTO mediumgaugecarportfolio(sourceId, name, englishName,parentId,type,url) VALUE(NULL,'$BRAND', '$englishName', NULL,'BRAND',NULL)";
        if ($pdo->exec($query)) {
            echo "ok";
        }
        $query = "SELECT id FROM mediumgaugecarportfolio WHERE `name`='$BRAND' AND type=\"BRAND\"";
        $rs = $pdo->query($query);
        while ($row = $rs->fetch()) {
            $parentIdb = $row['id'];
        }

        echo $car->find('dd .h3-tit')[0]->innertext;
        $ORGANIZA = $car->find('dd .h3-tit')[0]->innertext;
        $ORGANIZAs = $car->find('dd .h3-tit');
        foreach ($ORGANIZAs as $org) {
            $ORGANIZA = $org->innertext;
            //插入车系
            $query = "INSERT INTO mediumgaugecarportfolio(sourceId, name, englishName,parentId,type,url) VALUE(NULL,'$ORGANIZA', '$englishName', '$parentIdb','ORGANIZA',NULL)";
            if ($pdo->exec($query)) {
                echo "ok";
            }
            $query = "SELECT id FROM mediumgaugecarportfolio WHERE `name`='$ORGANIZA' AND type=\"ORGANIZA\"";
            $rs = $pdo->query($query);
            while ($row = $rs->fetch()) {
                $parentId = $row['id'];
            }
            echo "|";
            foreach ($org->next_sibling ()->find('li') as $cxvalue){
                if (isset($cxvalue->id)) {
                    echo (int)substr($cxvalue->id, 1);
                    $sourceId = (int)substr($cxvalue->id, 1);
                    if (isset($cxvalue->find('h4 a')[0]->innertext)) {
                        echo $cxvalue->find('h4 a')[0]->innertext;
                        $sourceIdname = $cxvalue->find('h4 a')[0]->innertext;
                    }
                    if (isset(explode("：", $cxvalue->find('div')[0]->plaintext)[1])) {
                        echo explode("：", $cxvalue->find('div')[0]->plaintext)[1];
                        $price = explode("：", $cxvalue->find('div')[0]->plaintext)[1];
                        echo "|";
                        //插入车系-车型
                        $query = "INSERT INTO mediumgaugecarportfolio(sourceId, name, englishName,parentId,type,url,price) VALUE($sourceId,'$sourceIdname', '$englishName', '$parentId','SERIES','http://car.autohome.com.cn/config/series/$sourceId.html','$price')";
                        if ($pdo->exec($query)) {
                            echo "okend" . time();
                        }
                    }

                }
            }
        }






    }


    echo "<br>";
}
//构造一个SQL查询  
//    $query = "INSERT INTO mediumgaugecarportfolio(sourceId, name, englishName,parentId,type,url) VALUE('$country', '$animal', '$cname')";

//执行该查询
//   $result = mysql_query($query) or die("Error in query: $query. ".mysql_error());

//插入操作成功后，显示插入记录的记录号
//  echo "记录已经插入， mysql_insert_id() = ".mysql_insert_id();
//关闭当前数据库连接


//var_dump($a[0]->innertext);
//取得指定位址的內容，並儲存至text

/*
$text=file_get_contents('http://www.autohome.com.cn/suv/');
//去除換行及空白字元（序列化內容才需使用）
$text=iconv("GB2312","UTF-8//IGNORE",$text) ;
$text=str_replace(array("\r","\n","\t","\s"), '', $text);
//取出div標籤且id為PostContent的內容，並儲存至陣列match、
//print($text);
$pattern='/<div class=\"h3-tit\".*?>.*?<\/div>/ism';
preg_match($pattern,$text,$match);
if(preg_match_all($pattern, $text, $match)){  
  // print_r($match);  
}else{  
   echo '0';  
}
//印出match[0]
var_dump($match[0]);
//print($match[0]);
*/
?>