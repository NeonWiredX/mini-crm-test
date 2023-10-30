<?php
namespace common\components;
use common\enums\Role;

class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public function can($role, $params = [], $allowCaching = true)
    {
        /** @var \common\models\User $user */
        $user = $this->identity;

        if (!$user) return false;

        if ($user->role === Role::ADMIN) {
            return true;
        }

        return $user->role === $role;
    }
}