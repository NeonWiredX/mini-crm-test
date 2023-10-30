<?php

use common\enums\Role;
use common\enums\UserStatus;
use common\models\User;
use \yii\db\Migration;

class m231030_203640_order_history extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('order_log', [
            'id' => $this->integer(),
            'v' => $this->integer(),

            'client_name' => $this->string(),
            'client_phone' => $this->string(),
            'good_id' => $this->integer(),
            'good_raw' => $this->json(),
            'status' => $this->string(),
            'comment' => $this->text(),
            'total_price' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer()
        ]);

        $this->addPrimaryKey('pk_order_log', 'order_log', ['id', 'v']);
    }

    public function down()
    {
    }
}
