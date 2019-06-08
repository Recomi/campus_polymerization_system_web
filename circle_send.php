<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 9:57
 */
include_once 'config.php';
header('Content-Type:application/json; charset=utf-8');
// 设置时区
date_default_timezone_set("PRC");
$result = true;     //校验成功或者失败，默认成功
//用于返回的json结果集
$returnArr = array(
    "result"     => false,  //最终执行结果，默认为失败
    "connected"  => false,  //是否连接数据库成功，默认为失败
    "haveValues" => true,   //是否含有必要非空的参数值，默认为有
    "execute_OK" => false   //是否执行sql成功，默认为失败
);

//取值，并判断非空值是否为空值
$content = isset($_POST['content']) ? $_POST['content'] : $returnArr['haveValues'] = false;
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : $returnArr['haveValues'] = false;
$time = date("Y-m-d H:i:s");

//建立连接
$conn = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, $mysql_db, $mysql_port);
if ($conn) {
    $returnArr['connected'] = true; //连接数据库成功

    //非空值有数据，即执行sql语句
    if ($returnArr['haveValues']) {
        mysqli_query($conn,'set names utf8');
        //执行插入数据库
        $sql  = "INSERT INTO circle_content (content, user_id, time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param("sss",  $content, $user_id, $time);
        $returnArr['execute_OK'] = ( $stmt ->execute() );   //执行成功或者失败
        $returnArr['result'] = $returnArr['execute_OK'];    //返回最终结果 等同于 执行结果
        $stmt ->close();
    }
}

$conn ->close();
//返回结果
echo json_encode($returnArr,JSON_UNESCAPED_UNICODE);
