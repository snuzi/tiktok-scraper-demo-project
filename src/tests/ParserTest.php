<?php
namespace tests;
use PHPUnit\Framework\TestCase;
use sabri\demo\tiktok\Parser;
use Exception;
use sabri\demo\tiktok\models\Post;
use sabri\demo\tiktok\models\SocialUser;

class ParserTest extends TestCase
{
    private $_parser;

    protected function setUp(): void
    {
        $this->_parser = new Parser();
    }

    public function testParseUser()
    {
        $data = $this->getData('user');
        $user = $this->_parser->parseUser($data['user']);
        $this->assertParsedUser($user, $data['user']);
    }

    public function testParseUserVideos()
    {
        $data = $this->getData('user_videos');
        $posts = $this->_parser->parseUserVideos($data);
        $this->assertParsedVideo($posts[0], $data['aweme_list'][0]);
    }

    public function testParseVideo()
    {
        $data = $this->getData('video');
        $post = $this->_parser->parseVideo($data['aweme_detail']);
        $this->assertParsedVideo($post, $data['aweme_detail']);
    }

    public function testParseSearchUser()
    {
        $data = $this->getData('search_user');
        $users = $this->_parser->parseSearchUsers($data['user_list']);
        $this->assertParsedUser($users[0], $data['user_list'][0]['user_info']);
    }

    private function getData($type) {
        return json_decode(file_get_contents(__DIR__ . '/data/' . $type . '.txt'), true);
    }

    private function assertParsedVideo(Post $post, array $data): void
    {
        $this->assertEquals($post->description, $data['desc']);
        $this->assertEquals($post->uid, $data['aweme_id']);
        $this->assertEquals($post->url, $data['share_info']['share_url']);
        $this->assertEquals($post->duration, $data['duration']);
        $this->assertEquals($post->nrComments, $data['statistics']['comment_count']);
        $this->assertEquals($post->nrInteractions, $data['statistics']['digg_count']);
        $this->assertEquals($post->thumbnail, $data['video']['origin_cover']['url_list'][0]);
        $this->assertEquals($post->userSocialId, $data['author_user_id']);
    }

    private function assertParsedUser(SocialUser $user, array $data): void
    {
        $this->assertEquals($user->fullName, $data['nickname']);
        $this->assertEquals($user->username, $data['unique_id']);
        $this->assertEquals($user->uid, $data['uid']);
        $this->assertEquals($user->bio, $data['signature']);
        $this->assertEquals($user->isVerified, $data['is_verified']);
        $this->assertEquals($user->image, $data['avatar_thumb']['url_list'][0]);
        $this->assertEquals($user->nrFollowing, $data['following_count']);
        $this->assertEquals($user->nrFans, $data['follower_count']);
        $this->assertEquals($user->nrHearts, $data['total_favorited']);
        $this->assertEquals($user->nrVideos, $data['aweme_count']);
    }
}
