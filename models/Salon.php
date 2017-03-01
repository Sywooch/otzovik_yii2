<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * This is the model class for table "salon".
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property integer $category
 * @property string $address
 * @property string $phone
 * @property string $worktime
 * @property string $url
 * @property string $meta
 * @property string $created_at
 * @property string $updated_at
 * @property UploadedFile $file
 */
class Salon extends \yii\db\ActiveRecord
{

    /**
     * @var UploadedFile
     */
    public $file;

    public $allfiles;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias', 'category'], 'required', 'message' => 'Это поле обязательно для заполнения.'],
            [['alias'], 'unique', 'message' => 'Автосалон с таким алиасом уже существует. Это поле должно быть уникальным.'],
            [['description', 'meta', 'coordinates'], 'string', 'message' => 'Поле должно быть текстовым.'],
            [['category'], 'integer', 'message' => 'Некорректное значение.'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 80, 'message' => 'Больше 80 символов нельзя.'],
            [['filename'], 'string'],
            [['avgrate'], 'string', 'max' => 5],
            [['alias', 'phone', 'worktime'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 100, 'message' => 'Слишком длинный адрес, сократите до 100 символов.'],
            [['url'], 'string', 'max' => 40],
            [['status'], 'integer'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4, 'message' => 'Многовато файлов! Должно быть не больше четырёх.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'alias' => 'alias',
            'description' => 'Текстовое описание',
            'category' => 'Город',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'worktime' => 'Время работы',
            'url' => 'Url',
            'meta' => 'Мета-данные',
            'created_at' => 'Актуально на:',
            'updated_at' => 'Актуально на:',
            'file' => 'Картинка салона',
            'avgrate' => 'Средняя оценка',
            'coordinates' => 'Координаты (для Яндекс.Карт)'
        ];
    }

    public function categoryLabels()
    {
        return [
            1 => 'Санкт-Петербург',
            2 => 'Москва'
        ];
    }

    public function getCategoryLabel()
    {
        $cities = $this->categoryLabels();
        return $cities[$this->category];
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['salon_id' => 'id']);
    }

    public function getApprovedComments($plus = array())
    {
        return $this->hasMany(Comments::className(), ['salon_id' => 'id'])
            ->where(['status' => '1'])
            ->orWhere(['in', 'id', $plus])
            ->orderBy('created_at');
    }

    /**
     * @return bool
     * ВРЕМЕННО!!!!!!!!!!!
     */
    public function getAllFiles()
    {
        if (mb_substr($this->filename, 0, 1) == '{') {
            $this->allfiles = json_decode($this->filename, true);
            $this->filename = $this->allfiles['1'];
        }
        else $this->allfiles[1] = $this->filename;
        return true;
        ////
    }
    /**
     * @return bool
     */
    public function upload()
    {
        $ruswords = array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я"," ");
        $translit = array("a","b","v","g","d","e","e","j","z","i","y","k","l","m","n","o","p","r","s","t","u","f","h","c","ch","sh","sh","","i","","e","y","ya","_");
        if ($this->validate()) {
            foreach ($this->file as $f) {
                $name = strtr(mb_strtolower($f->name), array_combine($ruswords,$translit));
                Yii::$app->fs->createDir('/'.$this->alias.'/');
                Yii::$app->fs->createDir('/'.$this->alias.'/thumb/');

                $f->saveAs('files/' . $this->alias . '/' . $name);
                Image::thumbnail('@webroot/files/' . $this->alias . '/' . $name, 250, null)
                    ->save(Yii::getAlias('@webroot/files/'.$this->alias.'/thumb/'.$name), ['quality' => 80]);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function refreshRate()
    {
        $comments = $this->getComments();

        $totalrate = intval($comments->sum('rate'));
        $count = intval($comments->count());
        $this->avgrate = number_format(($totalrate / $count), 1, '.', '');

        if ($this->save(false)) return true;
        else return false;
    }
    
    /**
     * @return bool
     */
    public function prepareUpload()
    {
        $ruswords = array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я"," ");
        $translit = array("a","b","v","g","d","e","e","j","z","i","y","k","l","m","n","o","p","r","s","t","u","f","h","c","ch","sh","sh","","i","","e","y","ya","_");
        $fileNameArray = array();
        $i = 1;
        foreach ($this->file as $singleFile){
            $fileNameArray[$i] = strtr(mb_strtolower($singleFile->name), array_combine($ruswords,$translit));
            $i++;
        }
        $this->filename = json_encode($fileNameArray);
        return true;
    }


}
