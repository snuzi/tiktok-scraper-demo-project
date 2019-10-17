<?php

namespace sabri\demo\tiktok\models;

/**
 * @property int $id
 * @property string $url
 * @property string $uploadData
 * @property integer $videoDuration
 * @property string $description
 * @property string $thumbnail
 * @property integer $nrInteractions
 * @property integer $nrComments
 */
class Post extends BaseModel
{

    public $timestamps = true;
    protected $table = 'posts';

    /**
     * returns the User that ownes this post
     */
    public function user()
    {
        return $this->hasOne(SocialUser::class, 'socialUserId');
    }
}
