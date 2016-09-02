<?php
require 'MyPDO.class.php';

/**
 * @param $svalue 搜索内容
 * @param $price 价格区间
 * @param $level 级别
 * @param $attr 属性
 */
function searchone($svalue, $price, $level, $attr = "自主")
{
    $db = MyPDO::getInstance('localhost', 'afei', '8823533', 'cars_db', 'utf8');
    $prices = explode("-", $price);
    $price = $prices[0];
    $priceb = isset($prices[1]) ? $prices[1] : "暂无";
    $query = 'SELECT DISTINCT `name`,englishName,price,priceb,sourceId FROM(SELECT  DISTINCT a.`name`, a.englishName,a.price,a.priceb,a.sourceId FROM `mediumgaugecarportfolio` a ,`mediumgaugecar` b  WHERE a.sourceId=b.seriesId AND  a.`name` LIKE \'%' . $svalue . '%\' AND b.valueName="级别" AND b.`value` LIKE \'%' . $level . 'SUV\' AND a.price BETWEEN ' . $price . ' AND ' . $priceb . ' UNION ALL SELECT  DISTINCT a.`name`, a.englishName,a.price,a.priceb,a.sourceId FROM `mediumgaugecarportfolio` a ,`mediumgaugecar` b  WHERE a.sourceId=b.seriesId AND  a.`name` LIKE \'%' . $svalue . '%\' AND b.valueName="级别" AND b.`value` LIKE \'%' . $level . 'SUV\' AND a.priceb BETWEEN ' . $price . ' AND ' . $priceb . ')t ORDER BY price ASC';
    $rs = $db->query($query);
    $db->destruct();
    return $rs;

}
function search($svalue, $price, $level, $attr = "自主")
{
    $db = MyPDO::getInstance('localhost', 'afei', '8823533', 'cars_db', 'utf8');
    $prices = explode("-", $price);
    $price = $prices[0];
    $priceb = isset($prices[1]) ? $prices[1] : "暂无";
    $query = 'SELECT DISTINCT `name`,englishName,price,priceb,sourceId FROM(SELECT  DISTINCT a.`name`, a.englishName,a.price,a.priceb,a.sourceId FROM `mediumgaugecarportfolio` a ,`mediumgaugecar` b  WHERE a.sourceId=b.seriesId AND  a.`name` LIKE \'%' . $svalue . '%\' AND b.valueName="级别" AND b.`value` LIKE \'%' . $level . 'SUV\' AND a.price BETWEEN ' . $price . ' AND ' . $priceb . ' UNION ALL SELECT  DISTINCT a.`name`, a.englishName,a.price,a.priceb,a.sourceId FROM `mediumgaugecarportfolio` a ,`mediumgaugecar` b  WHERE a.sourceId=b.seriesId AND  a.`name` LIKE \'%' . $svalue . '%\' AND b.valueName="级别" AND b.`value` LIKE \'%' . $level . 'SUV\' AND a.priceb BETWEEN ' . $price . ' AND ' . $priceb . ')t ORDER BY price ASC';
    $rs = $db->query($query);
    $db->destruct();
    return $rs;

}

//$query='SELECT  DISTINCT a.`name`, a.englishName,a.price,a.sourceId FROM `mediumgaugecarportfolio` a ,`mediumgaugecar` b  WHERE a.sourceId=b.seriesId AND  a.`name` LIKE \'%宝马%\' AND b.`value`=\'SUV\'';
$rs = search("奔驰", "20-30", "", $attr = "自主");
$rs = json_encode($rs);
echo $rs;



?>