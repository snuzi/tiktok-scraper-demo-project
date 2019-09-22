<?php
require __DIR__ . '/vendor/autoload.php';
(Dotenv\Dotenv::create(__DIR__ ))->load();
require 'src/config/db.php';

use sabri\tiktok\Crawler;

$crawler = new Crawler();

//$foundUser = $crawler->getUserByUsername('realmadrid');

// Crawl a direct video
//$video = $crawler->crawlVideo('6721977173101579526');

// Crawl a user profiel with his videos
//$crawledUser = $crawler->crawlUserWithVideos('6693776501107033094');

// Crawl 10 users ordered by oldest updated 
//$crawler->cronCrawlProfilesInDb();

// Crawl 10 videos ordered by oldest updated 
//$crawler->cronCrawlVideosInDb();