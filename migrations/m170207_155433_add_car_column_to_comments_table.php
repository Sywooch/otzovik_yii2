<?php

use yii\db\Migration;

/**
 * Handles adding car to table `comments`.
 */
class m170207_155433_add_car_column_to_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comments', 'car', $this->string(50));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('comments', 'car');
    }
}
