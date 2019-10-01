<?php

namespace tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use sabri\demo\tiktok\Crawler;
use sabri\demo\tiktok\models\Post;
use sabri\demo\tiktok\models\SocialUser;
use sabri\demo\tiktok\Parser;
use sabri\tiktok\TikTokApi;

class CrawlerTest extends TestCase
{
    private $_crawler;
    private $_parser;

    public function _testFindUserByUsername()
    {
        $data = $this->getData('search_user');

        $keyword = 'realmadrid';
        $mockTiktok = $this->mockTiktok([['searchUser', [$keyword], $data]]);

        $mockCrawler = $this->mockCrawler($mockTiktok);

        $foundUser = $mockCrawler->getUserByUsername($keyword);
        $this->assertNotEquals($foundUser, null);

        $keyword = 'cristiano';
        $foundUser = $mockCrawler->getUserByUsername($keyword);
        $this->assertEquals($foundUser, null);
    }

    private function getData($type)
    {
        return json_decode(file_get_contents(__DIR__ . '/data/' . $type . '.txt'), true);
    }

    private function mockTiktok(array $mockFunctions)
    {
        $tiktokClientMock = Mockery::mock(TikTokApi::class, [['bla' => 'bla']])->makePartial();
        foreach ($mockFunctions as $args) {
            list($shouldRecieve, $withArgs, $andReturn) = $args;

            $tiktokClientMock->shouldReceive($shouldRecieve)
                ->withArgs($withArgs)
                ->andReturn($andReturn);
        }

        return $tiktokClientMock;
    }

    private function mockCrawler($tiktokClientMock)
    {
        $crawlerMock = Mockery::mock(Crawler::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $crawlerMock->shouldReceive('getTiktokClient')
            ->andReturn($tiktokClientMock)
            ->shouldReceive('getParser')
            ->andReturn(new Parser());

        return $crawlerMock;
    }

    public function _testcrawlVideo()
    {
        $data = $this->getData('video');

        $uid = '123';
        $mockTiktok = $this->mockTiktok([['getPost', [$uid], $data]]);
        $mockCrawler = $this->mockCrawler($mockTiktok);

        $crawledVideo = $mockCrawler->crawlVideo($uid);

        // Video should be inserted
        $videoInDb = Post::where(['uid' => $crawledVideo->uid])->first();
        $this->assertTrue($crawledVideo->is($videoInDb));

        // Try to run again the same and check if data is just updated

        // Test if same video is just updated not duplicated
        $updatedDesc = 'bla bla';

        $data['aweme_detail']['desc'] = $updatedDesc;

        $mockTiktok = $this->mockTiktok([['getPost', [$uid], $data]]);
        $mockCrawler = $this->mockCrawler($mockTiktok);

        $crawledVideo = $mockCrawler->crawlVideo($uid);

        $videoInDb = Post::where(['uid' => $crawledVideo->uid])->get();

        // There should exist only one record for this video
        $this->assertEquals(1, count($videoInDb));
        // Description should be updated
        $this->assertEquals($updatedDesc, $videoInDb[0]->description);
    }

    public function testcrawlUserWithVideo()
    {
        $userInfoData = $this->getData('user');
        $userVideosData = $this->getData('user_videos');

        $uid = $userInfoData['user']['uid'];
        $mockTiktok = $this->mockTiktok([
            ['getUser', [$uid], $userInfoData],
            ['getUserVideos', [$uid], $userVideosData]
        ]);

        $mockCrawler = $this->mockCrawler($mockTiktok);
        $crawledUser = $mockCrawler->crawlUserWithVideos($uid);

        // User should be inserted
        $userInDb = SocialUser::where(['uid' => $crawledUser->uid])->first();
        $this->assertTrue($crawledUser->is($userInDb));

        // Test if same video is just updated not duplicated
        $updatedUserFullName = 'bla bla';
        $updateFirstVideoDescription = 'blabla';

        $userInfoData['user']['nickname'] = $updatedUserFullName;

        $videoUidToUpdate = $userVideosData['aweme_list'][0]['aweme_id'];

        $userVideosData['aweme_list'][0]['desc'] = $updateFirstVideoDescription;

        $mockTiktok = $this->mockTiktok([
            ['getUser', [$uid], $userInfoData],
            ['getUserVideos', [$uid], $userVideosData]
        ]);

        $mockCrawler = $this->mockCrawler($mockTiktok);

        $crawledUser = $mockCrawler->crawlUserWithVideos($uid);

        $userInDb = SocialUser::where(['uid' => $crawledUser->uid])->get();

        $userPostUpdatedInDb = Post::where([
            'userSocialId' => $crawledUser->uid,
            'uid' => $videoUidToUpdate
        ])->first();

        // There should exist only one record for this user
        $this->assertEquals(1, count($userInDb));
        // Full name should be updated
        $this->assertEquals($updatedUserFullName, $userInDb[0]->fullName);
        $this->assertEquals($updateFirstVideoDescription, $userPostUpdatedInDb->description);
    }

    protected function setUp(): void
    {
        $this->_parser = new Parser();
    }
}
