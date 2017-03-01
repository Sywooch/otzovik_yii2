<?php

use yii\db\Migration;

/**
 * Handles adding filename to table `salon`.
 */
class m170131_083259_add_filename_column_to_salon_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('salon', 'filename', $this->string(35));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('salon', 'filename');
    }
}
