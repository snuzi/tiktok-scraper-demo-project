<?php

namespace sabri\demo\tiktok;

use sabri\demo\tiktok\scraper\TikTokApi;
use sabri\demo\tiktok\models\Post;
use sabri\demo\tiktok\models\SocialUser;
use sabri\demo\tiktok\scraper\exceptions\LoginRequiredException;
use sabri\demo\tiktok\scraper\exceptions\InvalidResponseException;
use sabri\demo\tiktok\scraper\exceptions\EmptyResponseException;
use Exception;

/**
 * This is a wrapper for TiktokApi class. In this class users and videos can be
 * crawled and saved in database.
 */
class Crawler {

    /** @var Parser */
    private $_parser;

    public function __construct()
    {
        $this->_parser = new Parser();
    }

    /**
     * Use this to crawl a user's full profile together with his videos
     * Videos are scrapped and saved in DB
     * 
     * @param string $user_id user unique social id
     */
    public function crawlUserWithVideos($uid): SocialUser
    {
        $tiktok = $this->getTiktokClient();
        $socialUser = null;

        try {
            $userObject = $tiktok->getUser($uid);
            $socialUser = $this->getParser()->parseUser($userObject['user']);
            $socialUser = $this->saveUser($socialUser);
            $this->crawlUserVideos($socialUser->uid);
        } catch (LoginRequiredException | InvalidResponseException | EmptyResponseException $e) {
            // Log failure, but I decided to throw exception for now.
            throw new Exception($e->getMessage());
        }

        return $socialUser;
    }

    /**
     * Use this to crawl a user's all videos
     * Videos are scrapped and saved in DB
     * 
     * @param string $user_id user unique social id
     */
    protected function crawlUserVideos($user_id): void
    {
        $tiktok = $this->getTiktokClient();
        $postObjects = $tiktok->getUserVideos($user_id);

        $posts = $this->getParser()->parseUserVideos($postObjects);

        foreach ($posts as $post) {
            $this->savePost($post);
        }
    }

    /**
     * Use this to crawl just a single video
     * Video is scrapped and saved in DB
     * 
     * @param string $uid video unique social id
     */
    public function crawlVideo($uid): Post
    {
        $tiktok = $this->getTiktokClient();
        $postObject = $tiktok->getPost($uid);
  
        /** @var Post */
        $post = $this->getParser()->parseVideo($postObject['aweme_detail']);

        return $this->savePost($post); 
    }

    /**
     * Find a user based on username
     * 
     * Actually this does not work properly because tiktok blockes my requests after some times
     * To crawl a user for testing please use crawlUserWithVideos function
     * 
     * @param string $username
     * @return SocialUser if a user for this username is found otherwise null
     */
    public function getUserByUsername(string $username): ?SocialUSer
    {
        $tiktok = $this->getTiktokClient();
        $foundUsers = null;

        try {
            $foundUsers = $tiktok->searchUser($username);
        } catch (LoginRequiredException | InvalidResponseException | EmptyResponseException $e) {
            // Log failure, but I decided to throw exception for now.
            throw new Exception($e->getMessage());
        }

        $parsedUsers = $this->getParser()->parseSearchUsers($foundUsers['user_list']);

        // Find the exact match based on username
        foreach ($parsedUsers as $user) {
            if ($user->username === $username) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Create TiktokApi client with device info
     * 
     * @return TikTokApi client
     */
    protected function getTiktokClient(): TikTokApi
    {
        static $tiktokClient = null;

        $sessionQueryParams = [
            'device_id' => getenv('DEVICE_ID'),
            'iid' => getenv('IID'),
            'openudid' => getenv('OPENUDID')
        ];

        if (!$tiktokClient) {
            $tiktokClient = new TikTokApi($sessionQueryParams);
        }

        return $tiktokClient;
    }

    /**
     * Save post in database, save as new in case it does not exist, otherwise just update it
     */
    private function savePost(Post $post): Post
    {
        $postExists = Post::where([
            'uid' => $post->uid
            ])->first();   

        // Insert post if it is new
        if (!$postExists) {
            $post->save();
            $postExists = $post;

        // Update social user
        } else {
            $postExists->copyAttributesFromModel($post);
            $postExists->save();
        }

        return $postExists;
    }

    /**
     * Save user in database, save as new in case it does not exist, otherwise just update it
     */
    private function saveUser(SocialUser $user): SocialUser
    {
        $userExists = SocialUser::where([
            'uid' => $user->uid
            ])->first();  
          
        // Insert social user if it is new
        if (!$userExists) {
            $user->save();
            $userExists = $user;

        // Update social user
        } else {
            $userExists->copyAttributesFromModel($user);
            $userExists->save();
        }

        return $userExists;
    }

    /**
     * Crawl profiles which already exist in database
     * This can be a cron job
     */
    public function cronCrawlProfilesInDb($uid = false, int $limit = 10): void {

        if ($limit < 0) {
            $limit = 10;
        }
        
        $query = SocialUser::limit($limit);

        if ($uid) {
            $query->where(['uid' => $uid]);
        }

        // Get top 10 oldest updated profiles and re-crawl
        $socialUsers = $query->orderBy('updated_at', 'asc')
            ->get();

        // Perform a full profile with videos crawling
        foreach ($socialUsers as $socialUser) {
            $this->crawlUserWithVideos($socialUser->uid);
        }
    }

    /**
     * Crawl profiles which already exist in database
     * This can be a cron job
     * This task can be skipped, cronCrawlProfilesInDb function can do the job well
     */
    public function cronCrawlVideosInDb($uid = false, int $limit = 10): void {

        if ($limit < 0) {
            $limit = 10;
        }
        
        $query = Post::limit($limit);

        if ($uid) {
            $query->where(['uid' => $uid]);
        }

        // Get top 10 oldest updated posts and re-crawl
        $posts = $query->orderBy('updated_at', 'asc')
            ->get();
            
        foreach ($posts as $post) {
            $this->crawlVideo($post->uid);
        }
    }

    public function getParser()
    {
        return $this->_parser;
    }
}