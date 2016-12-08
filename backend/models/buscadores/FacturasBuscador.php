<?php

namespace backend\models\buscadores;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Facturas;

/**
 * FacturasBuscador represents the model behind the search form about `common\models\Facturas`.
 */
class FacturasBuscador extends Facturas
{
    public $empleado;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cantidad', 'created_at', 'updated_at'], 'integer'],
            [['empleado'], 'string'],
            [['codigo', 'concepto', 'descripcion', 'empleado'], 'safe'],
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
        $query = Facturas::find();

        // Importante: relacionar las facturas con los usuarios por medio de la relacion empleado
        $query->joinWith(['empleado']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Importante: aquí es cómo configuramos el orden asc o desc
        // La clave es el nombre del atributo en nuestra instancia "FacturasBuscador"
        $dataProvider->sort->attributes['empleado'] = [
            // Las tablas con las que nuestra relación está configurada
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cantidad' => $this->cantidad,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        //filtro de relaciones entre usuario y factura
        $query->andFilterWhere([
            'user.username' => $this->empleado,
        ]);

        $query->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'concepto', $this->concepto])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
