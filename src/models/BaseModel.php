<?php

namespace sabri\demo\tiktok\models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{

    public function copyAttributesFromModel(BaseModel $model)
    {
        $attributes = $model->getAttributes();
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
}
