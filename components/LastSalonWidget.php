<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Salon;

class LastSalonWidget extends Widget{
    public $limit = 5;
    public $salons;

    public function init(){
        parent::init();
        $this->salons = Salon::find()
            ->where(['status' => 1])
            ->limit($this->limit)
            ->orderBy('id DESC')
            ->all();
    }
    public function run(){
        return $this->render('lastSalon', ['salons' => $this->salons]);
    }
}
?>