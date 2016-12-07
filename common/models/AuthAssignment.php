<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_assignment';
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
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }

    /**
     * Verifica si un usuario ya tiene asignado un rol
     * @param $rol
     * @param $usuario
     * @return bool
     */
    public static function tienePermisosUsuario($rol, $usuario) {
        return static::find()->where('item_name = :rol and user_id = :usuario',
            [':rol' => $rol, ':usuario' => $usuario])->exists();
    }
}
