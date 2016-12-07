<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "authToken".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $created_at
 *
 * @property User $user
 */
class AuthToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authToken';
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
            [['user_id', 'token'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\scopes\AuthTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\scopes\AuthTokenQuery(get_called_class());
    }
}
