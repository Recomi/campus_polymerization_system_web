<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 9:57
 */
include_once 'config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With");
//header('Content-Type:application/json; charset=utf-8');
$returnArr = array(
    "result"     => false,  //最终执行结果，默认为失败
    "connected"  => false,  //是否连接数据库成功，默认为失败
    "haveValues" => true,   //是否含有必要非空的参数值，默认为有
    "execute_OK" => false,   //是否执行sql成功，默认为失败
    "data"       => array()
);
$data = array();
//建立连接
$conn = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, $mysql_db, $mysql_port);
if ($conn) {
    $returnArr['connected'] = true; //连接数据库成功

    $limit = isset($_GET["limit"]) ? $_GET["limit"] : 100;
    $offset = isset($_GET["offset"]) ? $_GET["offset"] : 0;

    //非空值有数据，即执行sql语句
    mysqli_query($conn,'set names utf8');
    //执行插入数据库
    $sql  = "select * from circle_content 
              order by thread_id desc
              LIMIT ? OFFSET ?";
//    $result = $conn->query($sql); //普通方案
    $stmt = $conn->prepare($sql);
    $stmt ->bind_param("ii",  $limit, $offset);
    $returnArr['execute_OK'] = ( $stmt->execute() );   //执行成功或者失败
    $returnArr['result'] = $returnArr['execute_OK'];    //返回最终结果 等同于 执行结果

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            $count = count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小
            for ($i = 0; $i < $count; $i++) {
                unset($rows[$i]);//删除冗余数据
            }
            array_push($data, $rows);
        }
    }
    $stmt ->close();
}

$conn ->close();
//返回结果
$returnArr['data'] = $data;
if (isset($_GET["callback"]))
    echo $_GET["callback"];
echo json_encode($returnArr,JSON_UNESCAPED_UNICODE);
