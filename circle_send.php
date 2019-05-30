<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 9:57
 */
include_once 'config.php';
header('Content-Type:application/json; charset=utf-8');
//建立连接
$conn = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, $mysql_db, $mysql_port);
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}
mysqli_query($conn,'set names utf8');

$result = true;

$content = isset($_POST['content']) ? $_POST['content'] : $result = false;
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : $result = false;
$thread_id = null;
$date=  date("Y-m-d H:i:s");

$sql  = "INSERT INTO circle_content (content, user_id, thread_id, time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
//
$stmt ->bind_param("ssis",  $content, $user_id, $thread_id, $time);
//插入信息
$send_time = $date;

//执行
$stmt ->execute();

$stmt ->close();
$conn ->close();