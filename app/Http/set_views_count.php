<?php
$mysqli = new mysqli('panter04.mysql.tools', 'panter04_db', 'GRyw2725', 'panter04_db');
$mysqli->set_charset("utf8");

$result = $mysqli->query('select * from user_videos');
$data = $result->fetch_all();
$video_id = implode(',', array_map(function($x){ return $x[3]; }, $data));


$response = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics,snippet&id=$video_id&key=AIzaSyCe7UB6H928GyEjHaIHeY5ZJiAmyxP_dYc"),true)['items'];

foreach ($response as $videoInfo)
{
    $views = (int) $videoInfo['statistics']['viewCount'];
    $title = $videoInfo['snippet']['title'];
    $id = $videoInfo['id'];

    $mysqli->query("update user_videos set views = {$views}, title = \"{$title}\" where video_id = \"{$id}\" ") or die($mysqli->error);
}