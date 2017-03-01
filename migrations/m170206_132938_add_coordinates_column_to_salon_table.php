<?php

use yii\db\Migration;

/**
 * Handles adding coordinates to table `salon`.
 */
class m170206_132938_add_coordinates_column_to_salon_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('salon', 'coordinates', $this->string(35));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('salon', 'coordinates');
    }
}
