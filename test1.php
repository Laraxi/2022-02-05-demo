<?php

require_once './vendor/autoload.php';
require_once './database.php';

use Illuminate\Database\Capsule\Manager as DB;

$res = DB::table("article")->paginate(2);
$title = DB::table("article")->pluck("title")->toArray();
dd($title);
