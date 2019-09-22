<?php
include_once __DIR__.'/../../vendor/autoload.php';
(Dotenv\Dotenv::create(__DIR__ . '/../..', '.env.test'))->load();
require __DIR__ . '/../config/db.php';

// Remove all current data in test database
\sabri\tiktok\models\Post::truncate();
\sabri\tiktok\models\SocialUser::truncate();