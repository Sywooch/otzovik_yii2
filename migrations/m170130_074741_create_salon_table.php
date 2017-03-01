<?php

use yii\db\Migration;

/**
 * Handles the creation of table `salon`.
 */
class m170130_074741_create_salon_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('salon', [
            'id' => $this->primaryKey(),
            'title' => $this->string(80)->notNull(),
            'alias' => $this->string(30),
            'description' => $this->text(),
            'category' => $this->integer()->notNull()->defaultValue(1),
            'address' => $this->string(100),
            'phone' => $this->string(30),
            'worktime' => $this->string(30),
            'url' => $this->string(40),
            'meta' => $this->text(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('salon');
    }
}
