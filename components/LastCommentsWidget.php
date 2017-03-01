<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Comments;

class LastCommentsWidget extends Widget{
    public $limit = 5;
    public $comments;

    public function init(){
        parent::init();
        $this->comments = Comments::find()
            ->where(['status' => '1'])
            ->limit($this->limit)
            ->orderBy('id DESC')
            ->all();
    }
    
    public function run(){
        return $this->render('lastComments', ['comments' => $this->comments]);
    }
}
?>