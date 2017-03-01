<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Salon;
use yii\db\Query;

class SideMenuWidget extends Widget{
    public $all;
    public $msk;
    public $spb;

    public function init(){
        parent::init();
        $this->spb = (new Query())
            ->from('salon')
            ->where('category = 1')
            ->andWhere(['status' => 1])
            ->count();
        $this->msk = (new Query())
            ->from('salon')
            ->where('category = 2')
            ->count();
        $this->all = $this->spb + $this->msk;
    }
    public function run(){
        return $this->render('sideMenu', [
            'all' => $this->all,
            'spb' => $this->spb,
            'msk' => $this->msk
        ]);
    }
}
?>