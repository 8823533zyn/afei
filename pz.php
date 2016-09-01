<?php
function getoption($str)
{
    $patten = "'var " . $str . "=(.*?)[^(?!&nbsp;)];'is";
    return $patten;
}

function getVarInjs($str, $patten, $withType = true)
{
    $patten_js = $withType ? "'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is" : "'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is";
    preg_match_all($patten_js, $str, $matches);

    foreach ($matches[1] as $m) {
        //过滤取值
        preg_match($patten, $m, $result);

        if (!empty($result[1]))
            return $result[1] . "}";
    }
    return false;
}

$pdo = new PDO("mysql:host=localhost;dbname=cars_db", "afei", "8823533");
$pdo->query('set names utf8;');

$query = "SELECT id,url FROM mediumgaugecarportfolio WHERE type='SERIES'";
$rs = $pdo->query($query);
while ($row = $rs->fetch()) {
    $Idurl = $row['url'];
    $seriesId = $row['id'];


//获取页面信息
    $text = file_get_contents($Idurl);
    $text = iconv("GB2312", "UTF-8//IGNORE", $text);
//var_dump($text);

    $patten = "'var config =(.*?);'is";
    $config = getVarInjs($text, getoption("config "));
    $option = getVarInjs($text, getoption("option "));
    $bag = getVarInjs($text, getoption("bag "));
    $color = getVarInjs($text, getoption("color "));
    $innerColor = getVarInjs($text, getoption("innerColor"));
    $innerColor = str_replace('\色', "黑色", $innerColor);
//echo $config."<br>";
//echo $option."<br>";
//echo $bag."<br>";
//echo $color."<br>";
//echo $innerColor . "<br>";


    $config = json_decode($config, true);
    $seriesId = $config['result']['seriesid'];
//解析congig
    foreach ($config['result']['paramtypeitems'] as $paramNames) {
        $paramName = $paramNames['name'];
        //echo $paramName;
        foreach ($paramNames['paramitems'] as $valueNames) {
            $valueName = $valueNames['name'];
            //echo $valueName;
            foreach ($valueNames['valueitems'] as $values) {

                //var_dump($values);
                $value = $values['value'];
                $specId = $values['specid'];
                //echo $value;
                $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE('$specId','$seriesId', '$paramName', '$valueName','$value')";
                if ($pdo->exec($query)) {
                    // echo "ok";
                }


            }
        }
    }
//解析$option
    $option = json_decode($option, true);
    $seriesId = $option['result']['seriesid'];
    foreach ($option['result']['configtypeitems'] as $paramNames) {
        $paramName = $paramNames['name'];
        //echo $paramName;
        foreach ($paramNames['configitems'] as $valueNames) {
            $valueName = $valueNames['name'];
            //echo $valueName;
            foreach ($valueNames['valueitems'] as $values) {

                //var_dump($values);
                $value = $values['value'];
                $specId = $values['specid'];
                //echo $value;
                $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE('$specId','$seriesId', '$paramName', '$valueName','$value')";
                if ($pdo->exec($query)) {
                    // echo "ok";
                }


            }
        }
    }


//解析$bag
    $bag = json_decode($bag, true);
    $seriesId = $bag['result']['seriesid'];


    $paramName = "选装包";

    foreach ($bag['result']['bagtypeitems'] as $valueNames) {

        foreach ($valueNames['bagitems'] as $values) {
            $valueName = $values['name'];
            $value1 = $values['description'] . ";" . $values['price'];
            //var_dump($value1);
            $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE(NULL,'$seriesId', '$paramName', '$valueName','$value1')";
            var_dump($query);
            if ($pdo->exec($query)) {
                // echo "okbag";
            }
            //var_dump($values);
            foreach ($values['valueitems'] as $val) {
                $specId = $val['specid'];
                $value = $val['value'];
                $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE('$specId','$seriesId', '$paramName', '$valueName','$value')";
                if ($pdo->exec($query)) {
                    // echo "ok";
                }
            }


        }
    }
//解析$color
    $color = json_decode($color, true);
//$seriesId=$color['result']['seriesid'];
    foreach ($color['result']['specitems'] as $paramNames) {
        $specId = $paramNames['specid'];
        $paramName = "外观颜色";
        //echo $paramName;
        foreach ($paramNames['coloritems'] as $valueNames) {
            $valueName = $valueNames['name'];
            $value = $valueNames['value'];
            //echo $valueName;
            //var_dump($values);
            //echo $value;
            $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE('$specId','$seriesId', '$paramName', '$valueName','$value')";
            if ($pdo->exec($query)) {
                //echo "ok";
            }
        }
    }
//解析$innerColor
//echo $innerColor;
    $innerColor = json_decode($innerColor, true);
//$seriesId=$color['result']['seriesid'];
//var_dump($innerColor);
    foreach ($innerColor['result']['specitems'] as $paramNames) {
        // var_dump($paramNames);
        $specId = $paramNames['specid'];
        $paramName = "内饰颜色";
        //echo $paramName;
        foreach ($paramNames['coloritems'] as $valueNames) {
            $valueName = $valueNames['name'];
            foreach ($valueNames['values'] as $value) {
                $value = $value;
                $query = "INSERT INTO mediumgaugecar(specId, seriesId, paramName,valueName,value) VALUE('$specId','$seriesId', '$paramName', '$valueName','$value')";
                if ($pdo->exec($query)) {
                    echo "ok";
                }
            }
        }
    }



echo $seriesId."<br>";

}

?>