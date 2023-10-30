<?php

namespace backend\modules\users\models;

use Yii;

class User extends \common\models\User
{

    public $password;

    public function rules()
    {
        $rules = parent::rules();
        if ($this->isNewRecord) {
            $rules = array_merge([
                ['password', 'required'],
                ['password', 'setupPassword'],
                ['auth_key', 'default', 'value' => Yii::$app->security->generateRandomString()],
            ], $rules);
        }

        return $rules;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return '';
    }

    public function setupPassword($attribute)
    {
        $this->setPassword($this->{$attribute});
    }
}