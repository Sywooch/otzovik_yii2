<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $salon_id
 * @property string $author
 * @property string $text
 * @property integer $rate
 * @property string $created_at
 *
 * @property Salon $salon
 */
class Comments extends \yii\db\ActiveRecord
{

    /**
     * @var string
     */
    public $captcha;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salon_id', 'text'], 'required', 'message' => 'Это поле обязательно для заполнения.'],
            [['rate'], 'required', 'message' => 'Пожалуйста, поставьте оценку.'],
            [['salon_id', 'rate', 'status'], 'integer'],
            [['text'], 'string'],
            [['created_at'], 'safe'],
            [['author', 'car'], 'string', 'max' => 50],
            [['captcha'], 'captcha', 'on'=>'captchaRequired', 'message' => 'Введите символы с картинки.'],
            [['salon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Salon::className(), 'targetAttribute' => ['salon_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'salon_id' => 'Salon ID',
            'author' => 'Имя',
            'text' => 'Текст отзыва',
            'rate' => 'Оценка',
            'car' => 'Приобретённый автомобиль',
            'created_at' => 'Отправлено',
            'captcha' => 'Введите текст с картинки:'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalon()
    {
        return $this->hasOne(Salon::className(), ['id' => 'salon_id']);
    }

}
