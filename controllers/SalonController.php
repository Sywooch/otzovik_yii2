<?php

namespace app\controllers;

use Yii;
use app\models\Salon;
use app\models\GeoHelper;
use app\models\CommentsSearch;
use app\models\SalonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Expression;

/**
 * SalonController implements the CRUD actions for Salon model.
 */
class SalonController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'delete', 'admin-view'],
                'rules' => [
                    [
                        'actions' => ['update', 'delete', 'admin-view'],
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
     * Lists all Salon models.
     * @param integer $category
     * @return mixed
     */
    public function actionIndex($category = 0, $view = 'list')
    {
        $searchModel = new SalonSearch();

        $params = Yii::$app->request->queryParams;
        $params["SalonSearch"]['category'] = $category == 0 ? '' : $category;

        $dataProvider = $searchModel->search($params);
        $title = $category == 0 ? "Все автосалоны" :
            ($category == 1 ? "Автосалоны Санкт-Петербурга" : "Автосалоны Москвы");
        if ($view == 'grid') $template = 'index';
        else $template = 'indexList';
        return $this->render($template, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'pager' => $dataProvider->pagination,
            'template' => $template,
            'title' => $title,
        ]);
    }

    /**
     * Lists all Salon models (only for Admins!).
     * @return mixed
     */
    public function actionAdminView ()
    {

        $searchModel = new SalonSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        return $this->render('adminView', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => 'Все автосалоны',
        ]);
    }

    /**
     * Displays a single Salon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $alias = $this->findModel($id)->alias;
        return $this->redirect(Yii::$app->urlManager->createUrl(['salon/view-alias', 'alias'=>$alias]), 301);
    }

    /**
     * Displays a single Salon model by its alias.
     * @param string $alias
     * @return mixed
     */
    public function actionViewAlias($alias)
    {
        $salon = $this->findModelByAlias($alias);
        $metainfo = json_decode($salon->meta);
        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $metainfo->desc,
        ]);
        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $metainfo->keys,
        ]);

        $my_comments = json_decode(Yii::$app->request->cookies->getValue('ids_json'));
        if (!isset($my_comments)) $my_comments = array();

        $commentSearchModel = new CommentsSearch();
        $params = array();
        $params["CommentsSearch"]["salon_id"] = $salon->id;
        $params["CommentsSearch"]["ids"] = $my_comments;
        if (\Yii::$app->user->can('admin') == false) $params["CommentsSearch"]["status"] = 1;
        $dataProvider = $commentSearchModel->search($params);



        $image_dir = Yii::getAlias('@web/files/'.$salon->alias);

        //// ВРЕМЕННО!!!!!
        $salon->getAllFiles();
        ////


        $this->view->params['jsonld'] = $this->getJsonLd($salon->id);

            return $this->render('view', [
                'model' => $salon,
                'comments' => $dataProvider->getModels(),
                'pager' => $dataProvider->pagination,
                'image_dir' => $image_dir,
                'my_comments' => $my_comments,
                'random' => $this->getRandom($salon->id, $salon->category),
        ]);
    }

    /**
     * Displays a single Salon model by its alias.
     * @param string $alias
     * @return mixed
     */
    public function actionSearchAjax()
    {
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && !empty($data)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $key = $data['key'];

            $searchModel = new SalonSearch();

            $params = Yii::$app->request->queryParams;
            $params["SalonSearch"]['title'] = $key;

            $dataProvider = $searchModel->search($params);
            $dataProvider->setPagination(false);

            // $dataProvider = $searchModel->search(['title' => $key]);
            return ['results' => $dataProvider->getModels()];
        }
        else throw new \yii\web\NotFoundHttpException();
    }
    /**
     * Tries to search for Yandex.Maps.
     * @param string $address
     * @return mixed
     */

    public function actionSearchAddress()
    {
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && !empty($data)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $address = $data['address'];

            $geoHelp = new GeoHelper($address);
            return['results' => $geoHelp->getCoords()];
        }
        else throw new \yii\web\NotFoundHttpException();
    }
    /**
     * Creates a new Salon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Salon();
        $post = Yii::$app->request->post();
        if (Yii::$app->user->can('admin')) $post['Salon']['status'] = 1;
        else  $post['Salon']['status'] = 0;
        $post['Salon']['meta'] = json_encode($post['Salon']['meta']);
        $post['Salon']['coordinates'] = json_encode($post['Salon']['coordinates']);
        if ($model->load($post)) {
            if (!empty($post['Salon']['address'])) {
                $geoHelp = new GeoHelper($post['Salon']['address']);
                $model->coordinates = $geoHelp->getCoords();
            }

            $model->file = UploadedFile::getInstances($model, 'file');
            $model->prepareUpload();
            
            if ($model->save() && $model->upload())
            {
                return $this->redirect(['view-alias', 'alias' => $model->alias]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Salon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $post['Salon']['meta'] = json_encode($post['Salon']['meta']);
            $post['Salon']['coordinates'] = json_encode($post['Salon']['coordinates']);

            $temp_array = explode(' ', trim($post['Salon']['filename']));
            $file_array = array();
            foreach ($temp_array as $k => $v) { $file_array[$k+1] = $v; }
            $post['Salon']['filename'] = json_encode($file_array);
        }
        else $post = null;

        if ($model->load($post)) {
            
            if (!empty($post['Salon']['address']) && (empty($post['Salon']['coordinates']['lat']) || empty($post['Salon']['coordinates']['lon']))) {
                $geoHelp = new GeoHelper($post['Salon']['address']);
                $model->coordinates = $geoHelp->getCoords();
            }
            elseif (!empty($post['Salon']['coordinates']['lat']) && !empty($post['Salon']['coordinates']['lon'])) {
                $model->coordinates = GeoHelper::encodeCoords($post['Salon']['coordinates']);
            }

            $model->file = UploadedFile::getInstances($model, 'file');
            if (!empty($model->file)) {
                $model->prepareUpload();
                $flag = $model->save() && $model->upload();
            }
            else $flag = $model->save();
            if ($flag)
            {
                return $this->redirect(['view-alias', 'alias' => $model->alias]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else {
            $model->getAllFiles();
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Salon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Salon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Salon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Salon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Salon model based on its custom field value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @return Salon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByAlias($alias)
    {
        $model = Salon::find()
            ->where(['alias'=>$alias]);
        if (Yii::$app->user->can('admin') === false) $model = $model->andWhere(['status' => 1]);
        $model = $model->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*
     *
     *HELPER TO MOVE DEALER BASE
     * @return bool
     */

    public function actionDirs()
    {
        echo 21;
        $contents = Yii::$app->fs->listContents();
        $all = Salon::find()
            ->where(['>', 'id', 39])
            ->all();


        foreach ($all as $s) {
            $file = json_decode($s->filename, true)[1];
            $temp = Yii::$app->fs->read('/new/sm_'.$file);
            Yii::$app->fs->put('/'.$s->alias.'/thumb/'.$file, $temp);
            $temp = Yii::$app->fs->read('/new/'.$file);
            Yii::$app->fs->put('/'.$s->alias.'/'.$file, $temp);
        }
        return true;
    }

    /*
     *
     * helper to get random items (which have a given category)
     * @return Salon array
     */

    protected function getRandom($id, $category = 1)
    {
        $salons = Salon::find()
            ->where(['category' => $category])
            ->andWhere(['!=', 'id', $id])
            ->orderBy(new Expression('rand()'))
            ->limit(3)
            ->all();
        return $salons;
    }

    /*
     * helper to get Json-LD micro-data
     *
     * @return string
     */

    protected function getJsonLd($id)
    {
        $salon = Salon::find()
            ->where(['id' => $id])
            ->one();
        $image = Yii::getAlias('@web/files/'.$salon->alias.'/thumb/'.json_decode($salon->filename, true)[1]);

        $ld = [
            "@context" => "http://schema.org",
            "@type" => "Product",
            "image" => $image,
            "name" => $salon->title,
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => number_format(floatval($salon->avgrate), 1, '.', ''),
                "bestRating" => "10",
                "ratingCount" => count($salon->comments),
            ]
        ];
        return json_encode($ld);
    }

    public function actionMoveDb()
    {
        $all = (new \yii\db\Query)->select('*')->from('showrooms')->all();
        foreach ($all as $s) {
            $city = $s['region'] == 1 ? "Санкт-Петербург" : "Москва";
            $meta = json_encode([
                'title' => $s['img_title'],
                'desc' => $s['about'],
                'keys' => $s['keywords']
            ]);
            Yii::$app->db->createCommand('INSERT INTO `salon` (`title`, `alias`, `description`, `category`, `address`, `phone`, `worktime`, `url`, `meta`)
    VALUES (:title, :alias, :desc, :cat, :add, :phone, :wrk, :url, :meta)',
                [
                    'title' => $s['title'],
                    'alias' => $s['ref'],
                    'desc' => $s['description'],
                    'cat' => $s['cities_id'],
                    'add' => 'г. '.$city.', '.$s['adress'],
                    'phone' => $s['phone'],
                    'wrk' => $s['workingtime'],
                    'url' => $s['web'],
                    'meta' => $meta
                ])->execute();
        }
    }

}
