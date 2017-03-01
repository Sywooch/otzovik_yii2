<?php

namespace app\controllers;

use Yii;
use app\models\Salon;
use app\models\Comments;
use yii\filters\auth\HttpBasicAuth;
use \yii\rest\ActiveController;
use yii\web\BadRequestHttpException;

/**
 * ApiController implements the CRUD actions for API
 */
class ApiController extends ActiveController
{

    /* Пивот-таблица алиасов и их локальных ID.
     * Вид: ID=>alias
     *
     * @var
     */
    protected $salon_array = [
        '1' => 'autoport',
        '2' => 'smolny',
        '3' => 'astoria-motors',
        '22' => 'testas',
        '23' => 'ralff',
        '25' => 'primavto',
        '186' => 'avtotsentr-primorskiy',
        '187' => 'atts-piter',
        '188' => 'ford-maksimum',
        '189' => 'avtotsentr-smolnyiy',
        '190' => 'astoriya-motors',
        '191' => 'yus-impeks',
        '192' => 'gostavto',
        '193' => 'accordauto',
        '194' => 'avtotsentr-yantar',
        '195' => 'alyans-tsentr',
        '196' => 'arenaauto',
        '197' => 'avtolayt',
        '198' => 'avtopole',
        '199' => 'sv-avangard',
        '200' => 'avanta',
        '201' => 'avis-avto',
        '202' => 'avtogermes',
        '203' => 'avtograd',
        '204' => 'avtotsentr-avtovo',
        '205' => 'kross-motors',
        '206' => 'fresh-auto',
        '207' => 'mas-motors',
        '208' => 'maksimum-',
        '209' => 'mosautogroup',
        '210' => 'nv-motors',
        '211' => 'ria-avto',
        '212' => 'ralf-(ralff)',
        '213' => 'kremlevskiy-avtoholding',
        '214' => 'avtoritet',
        '215' => 'alarm-motors',
        '216' => 'dakar',
        '217' => 'rolf-vitebskiy',
        '218' => 'rolf-oktyabrskaya',
        '219' => 'rolf-lahta',
        '220' => 'kosmonavt',
        '221' => 'hyundai-maksimum',
        '222' => 'mitsubishi-maksimum',
        '223' => 'dominanta',
        '224' => 'flagman',
        '225' => 'avant-motors',
        '226' => 'lite-motors-(layt-motors)',
        '227' => 'rosavto',
        '228' => 'avtotsentr-avtoport',
        '229' => 'askona-motors',
        '230' => 'arena-kars',
        '231' => 'pulkovo-motors',
        '232' => 'avanta-zelenograd',
        '233' => 'honda-maksimum-lahta',
        '234' => 'gamma-motors'
    ];

    /*
     * Тут Yii2-специфика, можно не обращать внимания
     */

    public $modelClass = 'app\models\Comments';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    /**
     * Выдаём все новые отзывы. Возвращает массив, содержащий, в свою очередь, массив новых отзывов и ID следующего (при добавлении) отзыва.
     * @param integer $lastid — локальный ID крайнего отзыва в сохранённой на сервере базе для данного сайта
     *
     * @return mixed
     */
    public function actionIndex($lastid = 0)
    {
        $comments = array();

        // Ищем в базе все отзывы с ID большим, чем сохранённый «последний ID» с сервера
        $all = Comments::find()
            ->where(['>', 'id', $lastid])
            ->all();

        foreach ($all as $c)
        {
            // Приводим к удобоваримому для сервера виду. Структура очень важна!
            $comments[] = [
                'id' => $c['id'],
                'alias' => $this->salon_array[$c['salon_id']],
                'name' => $c['author'],
                'text' => $c['text'],
                'date' => Yii::$app->formatter->asTimestamp($c['created_at']),
                'status' => $c['status'],
                'rate' => $c['rate'] * 10,
            ];
        }
        // Узнаём ID следующего добавленного отзыва.
        $last_id = Yii::$app->db->
        createCommand("SHOW TABLE STATUS LIKE 'comments'")
            ->query()->read()['Auto_increment'];

        // Запаковываем всё в массив
        $answer = compact('comments', 'last_id');
        return $answer;
    }

    /**
     * Изменяем отзыв.
     * @param integer $id — локальный ID изменяемого отзыва для данного сайта
     *
     * @return Comments $ans — возвращаем изменённый отзыв
     */
    public function actionMod($id)
    {
        if (empty($id)) throw new BadRequestHttpException;

        // Парсим из JSON-тела запроса PHP-массив.
        $content = Yii::$app->request->rawBody;
        $model = json_decode($content);

        // Приводим в нормальный для сохранения вид
        $text = $model->text;
        $status = $model->status;
        $author = $model->name;
        $salon = array_flip($this->salon_array)[$model->alias];
        $date = Yii::$app->formatter->asDate($model->date, 'yyyy-MM-dd HH:mm:ss');
        $rate = round(intval($model->rate) / 10);

        // Выбираем из базы отзыв, который меняем
        $original = Comments::find()
            ->where(['id' => $id])
            ->one();

        // Собственно, меняем...
        $original->salon_id = $salon;
        $original->text = $text;
        $original->status = $status;
        $original->created_at = $date;
        $original->author = $author;
        $original->rate = $rate;

        // Сохраняем...
        $original->save(false);

        // Возвращаем.
        $ans = Comments::find()
            ->where(['id' => $id])
            ->one();

        return $ans;
    }

    /**
     * Создаём новый отзыв. Либо возрвращаем ID созданного отзыва, либо ошибки.
     *
     * @return mixed
     */

    public function actionCreate()
    {
        // Парсим из JSON-тела запроса PHP-массив.
        $content = Yii::$app->request->rawBody;
        if (empty($content)) throw new BadRequestHttpException;
        $model = json_decode($content);

        // Приводим в нормальный для сохранения вид
        $text = $model->text;
        $status = $model->status;
        $author = $model->name;
        // важная строчка:
        $salon = array_flip($this->salon_array)[$model->alias];
        $date = Yii::$app->formatter->asDate($model->date, 'yyyy-MM-dd HH:mm:ss');
        $rate = round(intval($model->rate) / 10);


        // Сохраняем в базу:
        $original = new Comments;

        $post = array();
        $post['Comments']['salon_id'] = $salon;
        $post['Comments']['text'] = $text;
        $post['Comments']['status'] = $status;
        $post['Comments']['created_at'] = $date;
        $post['Comments']['author'] = $author;
        $post['Comments']['rate'] = $rate;
        if ($original->load($post) && $original->save()) {
            // Если всё норм - возвращаем локальный ID
            return $original->id;
        }
        // Если нет - выводим ошибки
        else return $original->getErrors();
    }


    /**
     * Функция-хелпер для того, чтобы выдать массив-список алиасов автосалонов.
     *
     * @return string
     */

    public function actionListSalons()
    {
        $salons = Salon::find()->all();
        foreach ($salons as $s) {
            echo "'".$s['id']."' => '".$s['alias']."',<br>";
        }
    }

}
