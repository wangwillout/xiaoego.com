<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\Building;

class BuildingSearch extends Building
{
    public $date;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'school_id', 'status', 'date'], 'safe'],
            [['date'], 'trim']
        ];
    }

    public function search($params)
    {
        $query = Building::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $dateBegin = strtotime($this->date);
        $dateEnd = $dateBegin + 86400;

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['school_id' => $this->school_id])
              ->andFilterWhere(['status' => $this->status])
              ->andFilterWhere(['>=', 'created_at', $this->date ? $dateBegin : null])
              ->andFilterWhere(['<', 'created_at', $this->date ? $dateEnd : null]);

        return $dataProvider;
    }
}