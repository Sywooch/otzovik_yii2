<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comments`.
 */
class m170130_095238_create_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'salon_id' => $this->integer()->notNull(),
            'author' => $this->string(50),
            'text' => $this->text()->notNull(),
            'rate' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-comment-salon_id',
            'comments',
            'salon_id',
            'salon',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-comment-salon_id',
            'comments'
        );
        $this->dropTable('comments');
    }
}
