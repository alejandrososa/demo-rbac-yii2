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
 * @property integer $empleado
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $empleado0
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
            [['cantidad', 'empleado'], 'integer'],
            [['concepto', 'empleado',], 'required'],
            [['descripcion'], 'string'],
            [['codigo'], 'string', 'max' => 20],
            [['concepto'], 'string', 'max' => 70],
            [['codigo'], 'unique'],
            [['empleado'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['empleado' => 'id']],
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
            'descripcion' => 'Descripcion',
            'empleado' => 'Empleado',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado0()
    {
        return $this->hasOne(User::className(), ['id' => 'empleado']);
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
