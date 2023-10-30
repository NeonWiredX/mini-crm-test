<?php

use common\enums\Role;
use common\enums\UserStatus;
use common\models\User;
use \yii\db\Migration;

class m231029_184932_user_roles_and_orders extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn(
            User::tableName(),
            'role',
            $this
                ->string()
                ->after('email')
                ->defaultValue(Role::MANAGER)
        );

        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@admin.ru';
        $user->status = UserStatus::STATUS_ACTIVE;
        $user->role = Role::ADMIN;
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        $user->save();


        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'client_name' => $this->string(),
            'client_phone' => $this->string(),
            'good_id' => $this->integer(),
            'good_raw' => $this->json(),
            'status' => $this->string(),
            'comment' => $this->text(),
            'total_price' => $this->integer(),
            'v' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer()
        ], $tableOptions);

        $this->createTable('good', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_good',
            'order',
            'good_id',
            'good',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->batchInsert('good', ['name', 'price'], [
            ['яблоки', 10000], //100 рублей
            ['апельсины', 20000], //200 рублей
            ['мандарины', 30000], //100 рублей
        ]);

    }

    public function down()
    {
        $this->dropForeignKey('fk_order_good', 'order');
        $this->dropTable('good');
        $this->dropTable('order');
        User::findOne(['username' => 'admin'])->delete();
    }
}
