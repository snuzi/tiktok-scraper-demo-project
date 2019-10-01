<?php

namespace sabri\demo\tiktok\models;

/**
 * @property int $id
 * @property string $uid
 * @property string $username
 * @property string $fullName
 * @property bool $isVerified
 * @property string $bio
 * @property string $image
 * @property integer $nrHearts
 * @property integer $nrFans
 * @property integer $nrFollowing
 * @property integer $nrVideos
 */
class SocialUser extends BaseModel
{
    public $timestamps = true;
    protected $table = 'social_users';

    /**
     * returns the Posts that this user owned
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'socialUserId');
    }
}
