<?php

namespace app\controllers;

use Yii;
use app\models\Comments;
use app\models\CommentsSearch;
use yii\base\ViewContextInterface;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Cookie;

/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends Controller implements ViewContextInterface
{
    public function getViewPath()
    {
        return Yii::getAlias('@app/views/comment');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'delete', 'view', 'index'],
                'rules' => [
                    [
                        'actions' => ['update', 'delete', 'view', 'index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Comments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comments model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comments();

        $post = Yii::$app->request->post();
        if (\Yii::$app->user->can('admin')) $post['Comments']['status'] = 1;

        if ($model->load($post) && $model->save()) {
            $ids_json = Yii::$app->request->cookies->getValue('ids_json');
            $cookie_r = json_decode($ids_json);
            if (empty($ids_json)) $newcookie_r = [$model->id];
            else $newcookie_r = array_merge([$model->id], $cookie_r);

            $cookie = new Cookie([
                'name' => 'ids_json',
                'value' => json_encode($newcookie_r),
                'expire' => time() + 86400 * 365,
            ]);
            
            \Yii::$app->getResponse()->getCookies()->add($cookie);

            Yii::$app->mailer->compose()
                ->setTo(Yii::$app->params['adminEmail'])
                ->setFrom(['sent@bestautorate.ru' => $post['Comments']['author']])
                ->setSubject('Отзыв об автосалоне')
                ->setTextBody($post['Comments']['text'])
                ->send();
            if (Yii::$app->request->isAjax) return json_encode(['status' => 'success']);
            else return $this->redirect(Yii::$app->urlManager->createUrl(['salon/view', 'id'=>$model->salon_id]));
        } else {
            if (Yii::$app->request->isAjax) return json_encode(['status' => 'error']);
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post_array = Yii::$app->request->post();
        if (!empty($post_array)){
            $date = Yii::$app->formatter->asDate($post_array["Comments"]['created_at'], 'yyyy-MM-dd HH:mm:ss');
            $post_array["Comments"]['created_at'] = $date;
        }
        if ($model->load($post_array) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Approves a comment.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionApprove($id, $approved = 1)
    {
        $model = $this->findModel($id);

        $model->status = $approved;

        if ($model->save(false)) {

            if ($model->salon->refreshRate())
            {
                return $this->redirect(Yii::$app->urlManager->createUrl(['salon/view', 'id'=>$model->salon_id]));
            }
            else return $this->render('update', [
                    'model' => $model,
                ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $salon = $this->findModel($id)->salon;
        $this->findModel($id)->delete();
        $salon->refreshRate();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * API command for getting all non-moderated comments.
     * @param integer $lastid
     * @return array of Salon
     */
    public function getNewComments($lastid = 0)
    {
        
    }
}
