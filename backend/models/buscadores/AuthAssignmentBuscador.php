<?php

namespace backend\models\buscadores;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AuthAssignment;

/**
 * AuthAssignmentBuscador represents the model behind the search form about `common\models\AuthAssignment`.
 */
class AuthAssignmentBuscador extends AuthAssignment
{
    public $usuario;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id', 'usuario'], 'safe'],
            [['created_at'], 'integer'],
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
        $query = AuthAssignment::find();

        // Importante: relacionar las facturas con los usuarios por medio de la relacion usuario
        $query->joinWith(['usuario']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Importante: aquí es cómo configuramos el orden asc o desc
        // La clave es el nombre del atributo en nuestra instancia "FacturasBuscador"
        $dataProvider->sort->attributes['usuario'] = [
            // Las tablas con las que nuestra relación está configurada
            'asc' => ['user.email' => SORT_ASC],
            'desc' => ['user.email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
        ]);

        //filtro de relaciones entre usuario y factura
        $query->andFilterWhere([
            'like','user.email', $this->usuario,
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
