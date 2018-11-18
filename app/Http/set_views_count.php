<?php

$db = new \PDO("mysql:host=panter04.mysql.tools;dbname=panter04_db;charset=utf8", 'panter04_db', 'GRyw2725');

$data = $db->query('select * from user_videos')->fetchAll();
$parts = array_chunk($data, 50);

foreach ($parts as $part) {
    $video_id = implode(',', array_map(function ($x) {
        return $x[3];
    }, $part));
    $response = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics,snippet&id=$video_id&key=AIzaSyCe7UB6H928GyEjHaIHeY5ZJiAmyxP_dYc"), true)['items'];

    foreach ($response as $videoInfo) {
        $views = (int)$videoInfo['statistics']['viewCount'];
        $title = $videoInfo['snippet']['title'];
        $id = $videoInfo['id'];

        $result = $db->prepare('update user_videos set views = :views, title = :title where video_id = :video_id');
        $result->bindParam(':views', $views);
        $result->bindParam(':title', $title);
        $result->bindParam(':video_id', $id);
        if(!$result->execute()){
            echo "\n PDO::errorInfo(): \n";
            print_r($result->errorInfo());
        }

        echo "Video with ID - {$id} was updated successful. Title - {$title}, Views - {$views}, VideoId - {$id}.\n";
    }
}