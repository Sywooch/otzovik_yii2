<?php

use yii\db\Migration;

/**
 * Handles adding status to table `salon`.
 */
class m170221_100827_add_status_column_to_salon_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('salon', 'status', $this->string(2));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('salon', 'status');
    }
}
