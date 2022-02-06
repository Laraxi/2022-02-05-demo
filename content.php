<?php
require_once './vendor/autoload.php';
set_time_limit(0);

use QL\QueryList;

$dsn = "mysql:host=192.168.56.200;dbname=caiji;charset=utf8";
$db = new PDO($dsn, 'root', '123456'); //初始化一个PDO对象
$sql = "select id,link from article";
$res = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$start = time();
echo '爬虫开始' . '<br />';

foreach ($res as $value) {
    $rules = [
        'content' => [' .content', 'html'],
    ];
    $data = QueryList::Query($value['link'], $rules)->getData(function ($item) use ($value, $db) {

        $content = htmlspecialchars($item['content']);
        $sql = "update article set content = ? where id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$content, $value['id']]);
    });
}
echo '爬虫结束' . '<br />';
$end = time();
$time = $end - $start;
echo '当前执行' . $time . '秒';