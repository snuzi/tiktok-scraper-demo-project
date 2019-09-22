## Setup steps

### Tiktok device parameters
In order to make requests to Tiktok API, some extra device parameters are needed. These parameters should be extracted from your mobile phone by using a man in the middle proxy. I used PacketCapture for Android, you may use any proxy application you like. Get the following parameters: device_id, iid, openudid.

### Setup .env and .env.test files.
- `.env` is used for the application
- `.env.test` is used by tests.

### Run docker containers
In root of the project run the following command to start the docker containers:
`docker-compose up`

### Import database
Do this step in case database is not auto loaded.

### Access containers:
Application container :  
`docker exec -it tiktok-scraper bash`
Mysql container:  
`docker exec -it tiktok-mysql bash`

### Export database
Dump database from a container to your machine:
`docker exec tiktok-mysql sh -c 'exec mysqldump tiktok -uroot -proot' > ~/tiktok.sql`


## How to use this demo application

### Install packages
From you host machine, install all packages by running in the root of the project:
`composer install`
*PHP 7.3 may be required. If you do not have PHP 7.3, download composer.phar and move to the project root. 
Then do inside of the application container by runing:
`docker exec -it tiktok-scraper bash`
then install packages:
`php composer.phar install`

### Run crawler:
Go inside tiktok-scraper container
`docker exec -it tiktok-scraper bash`
Execute:
`php demo.php`
Modify demo.php according to your needes.


### Run tests
 Go inside of the application container by runing:
`docker exec -it tiktok-scraper bash`
then run tests:
`vendor/bin/phpunit`

## Crawler API
- `crawlUserWithVideos($uid)` : Crawl a user profile together with his videos. `$uid` is user Tiktok unique id. This will crawl and store in db user's profile and videos.
- `crawlVideo($uid)` : Crawl and store in db a video. `$uid` is video Tiktok unique id.

- `getUserByUsername($username)` : Searches on Tiktok for a user and returns a user model which exactly matches the specified username, otherwise returns `null`.

- `cronCrawlProfilesInDb()` : This will re-crawl last 10 oldest users with videos thay own from database. This can be a cron job task. 

- `cronCrawlVideosInDb()` : This will re-crawl last 10 oldest videos from database. This can be a cron job task.

## Tiktok scraper
Tiktok scraper is found in the `src/scraper`
### Tiktok API
- `getUser($uid)` Returns user profile data. `$uid` is user's Tiktok unique id.
- `getUserVideos($uid)` Returns a list of user videos. `$uid` is user's Tiktok unique id.
- `searchUser($keyword)` Returns user search results.
- `getVideo($uid)` Returns a video datails. `$uid` is video Tiktok unique id.

### Access database from host machine:
`host` = `localhost`
`port` = `33066`
`username` = `root`
`password` = `root`
