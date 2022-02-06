<?php
require_once './vendor/autoload.php';
set_time_limit(0);

use QL\QueryList;

$start = time();
echo '爬虫开始' . PHP_EOL;
//$urlArr = range(1, 2);

for ($i = 1; $i <= 2; $i++) {
    $urlArr[] = "https://www.php.cn/article.html?p=" . $i;
}

function Img1($item)
{
    $ext = pathinfo($item, PATHINFO_EXTENSION);
    $path = 'upload/' . md5(time()) . '.' . $ext;
    file_put_contents($path, file_get_contents($item));

}

//多线程扩展
QueryList::run('Multi', [
    //待DOM解析链接集合
    'list' => $urlArr,
    'curl' => [
        'opt' => array(
            //这里根据自身需求设置curl参数
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            //........
        ),
        //设置线程数
        'maxThread' => 100,
        //设置最大尝试数
        'maxTry' => 3
    ],
    'success' => function ($a) {
        $rules = [
            'title' => [' .ar-right>h2 a', 'text'],
            'info' => ['.ar-right .info', 'html'],
            'img' => ['.ar-img img', 'src', ''],
            'date' => [' .ar-span span:eq(2)', 'html', '-i'],
            'link' => [' .ar-right>h2 a', 'href', '', function ($item) {
                return 'https://www.php.cn' . $item;
            }],
        ];
        $range = '.article-list';
        $ql = QueryList::Query($a['content'], $rules, $range);
        $data = $ql->getData(function ($item){
            $ext = pathinfo($item['img'], PATHINFO_EXTENSION);
            $path = 'upload/' . md5(time()) . '.' . $ext;
            file_put_contents($path, file_get_contents($item['img']));
        });
        //打印结果，实际操作中这里应该做入数据库操作
//        print_r($data);
    }
]);

//foreach ($r as $key => $item) {
//    $key = $key + 1;
//    echo '当前采集文章是' . $key . '页' . PHP_EOL;
//    $url = "https://www.php.cn/article.html?p=" . $item;
//
//    $data = QueryList::Query($url, $rules, $range)->getData(function ($item) {
////        echo '<pre>';
////        print_r($item);
////        $dsn = "mysql:host=192.168.56.200;dbname=caiji;charset=utf8";
////        $db = new PDO($dsn, 'root', '123456'); //初始化一个PDO对象
////        $sql = "insert ignore into article (title,info,img,date,link) values (?,?,?,?,?)";
////        $stmt = $db->prepare($sql);
////        $stmt->execute([$item['title'], $item['info'], $item['img'], $item['date'], $item['link']]);
//    });
//}

function download($url, $path = 'upload/')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    $file = curl_exec($ch);
    curl_close($ch);
    $filename = pathinfo($url, PATHINFO_BASENAME);
    $resource = fopen($path . $filename, 'a');
    fwrite($resource, $file);
    fclose($resource);
}


function downImg($file, $newFile)
{
//    $file = 'https://img.php.cn/upload/article/000/000/024/61ecc27669ca7680.jpg';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $tmp = curl_exec($ch);
    file_put_contents($newFile, $tmp);
}

echo '爬虫结束' . PHP_EOL;
$end = time();
$time = $end - $start;
echo '当前执行' . $time . '秒' . PHP_EOL;;