<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "facturas".
 *
 * @property integer $id
 * @property string $codigo
 * @property integer $cantidad
 * @property string $concepto
 * @property string $descripcion
 * @property integer $empleado_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $empleado
 */
class Facturas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facturas';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            //fechas
            'attributeStamp' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function($event){
                    return time();
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cantidad', 'empleado_id'], 'integer'],
            [['concepto', 'empleado_id',], 'required'],
            [['descripcion'], 'string'],
            [['codigo'], 'string', 'max' => 20],
            [['concepto'], 'string', 'max' => 70],
            [['codigo'], 'unique'],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['empleado_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'cantidad' => 'Cantidad',
            'concepto' => 'Concepto',
            'descripcion' => 'Descripción',
            'empleado_id' => 'Empleado',
            'created_at' => 'Creado',
            'updated_at' => 'Editado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(User::className(), ['id' => 'empleado_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\scopes\FacturasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\scopes\FacturasQuery(get_called_class());
    }
}
