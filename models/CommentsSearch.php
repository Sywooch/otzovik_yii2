<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\models\Comments;

/**
 * CommentsSearch represents the model behind the search form about `app\models\Comments`.
 */
class CommentsSearch extends Comments
{
    /**
     * For search
     * @var array ids
     */
    public $ids;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'salon_id', 'rate', 'status'], 'integer'],
            [['author', 'text', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comments::find()
            ->orderBy('status')
            ->addOrderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        $this->load($params);
        if(!empty($params["CommentsSearch"]["ids"])) $this->ids = $params["CommentsSearch"]["ids"];
        else $this->ids = array();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'rate' => $this->rate,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ])->orFilterWhere(['IN', 'id', $this->ids])->andFilterWhere(['salon_id' => $this->salon_id]);



        $query->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
