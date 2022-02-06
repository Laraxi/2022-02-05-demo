<?php
$file = 'https://img.php.cn/upload/article/000/000/024/61ecc27669ca7680.jpg';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$tmp = curl_exec($ch);
file_put_contents('a.png', $tmp);