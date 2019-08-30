<?php
    //连接本地的 Redis 服务
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   echo "Connection to server successfully";
   echo "<hr>";
   //存储数据到列表中
   // 获取数据并输出
   $arList = $redis->keys("*");
   echo "Stored keys in redis:: ";
   print_r($arList);
?>
