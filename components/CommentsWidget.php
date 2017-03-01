<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Comments;

class CommentsWidget extends Widget{
    public $comments;
    public $admin;
    public $my_comments;
    public $pager;

    public function init(){
        parent::init();
        if($this->comments === null) {
            return false;
        }
    }

    public function run(){
        return $this->render('comments', [
            'comments' => $this->comments,
            'admin' => $this->admin,
            'my_comments' => $this->my_comments,
            'pager' => $this->pager,
        ]);
    }
}
?>