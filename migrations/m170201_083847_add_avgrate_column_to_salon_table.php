<?php

use yii\db\Migration;

/**
 * Handles adding avgrate to table `salon`.
 */
class m170201_083847_add_avgrate_column_to_salon_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('salon', 'avgrate', $this->string(5)->defaultValue('0'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('salon', 'avgrate');
    }
}
