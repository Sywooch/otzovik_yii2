<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class SiteConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Поле',
            'value' => 'Значение',
            'comment' => 'Комментарий',
        ];
    }

    public function v($key)
    {
        $search = self::find()->where(['key' => $key])->one();
        return $search->value;
    }
}
