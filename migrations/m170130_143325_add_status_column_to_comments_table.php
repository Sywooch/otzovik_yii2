<?php

use yii\db\Migration;

/**
 * Handles adding status to table `comments`.
 */
class m170130_143325_add_status_column_to_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comments', 'status', $this->integer()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('comments', 'status');
    }
}
