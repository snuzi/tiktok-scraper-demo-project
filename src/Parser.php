<?php

namespace sabri\demo\tiktok;

use sabri\demo\tiktok\models\Post;
use sabri\demo\tiktok\models\SocialUser;
use sabri\demo\tiktok\exceptions\UserProfileException;

class Parser {
    /**
     * Parse a video object comming from Tiktok APi
     * 
     * @param array $video video object
     * 
     * @return Post
     */
    public function parseVideo(array $video): Post
    {
        $post = new Post();

        $post->description = $video['desc'];
        $post->uid = $video['aweme_id'];
        $post->url = $video['share_info']['share_url'];
        $post->duration = $video['duration'];
        $post->nrComments = $video['statistics']['comment_count'];
        $post->nrInteractions = $video['statistics']['digg_count'];
        $post->created_at = date("Y-m-d H:i:s", $video['create_time']);
        $post->thumbnail = $video['video']['origin_cover']['url_list'][0];
        $post->userSocialId = $video['author_user_id'];

        // Find source URL, url without HTTPS and not from musical.ly does not work in EU
        $sourceUrls = $video['video']['download_addr']['url_list'];
        $url = '';
        foreach ($sourceUrls as $sourceUrl) {

            if (strpos($sourceUrl, 'https://') !== false
                && strpos($sourceUrl, 'musical.ly') !== false
            ) {
                $url = $sourceUrl;
            }
        }
        if (!$url) {
            // TODO Investigate why some url does not work;
            $url = $sourceUrls[0];
        }
        $post->uploadData = $url;

        return $post;
    }

    /**
     * Parse a user object comming from Tiktok APi
     * 
     * @param array $object user object
     * 
     * @return SocialUser
     */
    public function parseUser(array $object): SocialUser
    {
        $user = new SocialUser();

        $user->fullName = $object['nickname'];
        $user->username = $object['unique_id'];
        $user->uid = $object['uid'];
        $user->bio = $object['signature'];
        $user->isVerified = $object['is_verified'];
        $user->image = $object['avatar_thumb']['url_list'][0];
        $user->nrFollowing = $object['following_count'];
        $user->nrFans = $object['follower_count'];
        $user->nrHearts = $object['total_favorited'];
        $user->nrVideos = $object['aweme_count'];

        return $user;
    }

    /**
     * Parse a user video objects comming from Tiktok APi
     * 
     * @param array $object video list object
     * 
     * @return Post[] Array of posts
     */
    public function parseUserVideos(array $object): array
    {
        $videos = $object['aweme_list'];
        $videoList = [];

        foreach ($videos as $videoObject) {
            $videoList[] = $this->parseVideo($videoObject);
        }

        return $videoList;
    }

    /**
     * Parse a user list comming from Tiktok APi
     * 
     * @param array $object user list from search user API endpoint
     * 
     * @return SocialUser[] Array of social ussers
     */
    public function parseSearchUsers(array $list): array
    {
        /** @var SocialUser[] */
        $userList = [];

        foreach ($list as $user) {
            $userList[] = $this->parseUser($user['user_info']);
        }

        return $userList;
    }
}
