<?php

namespace common\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Time;

/**
 * TimeSearch represents the model behind the search form about `common\models\Time`.
 */
class TimeSearch extends Time
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time'], 'string'],
            [['date', 'datetime'], 'safe'],
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
        $query = Time::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'date' => $this->date,
            'datetime' => $this->datetime,
        ]);

        return $dataProvider;
    }
}
