<?php

namespace common\models\scopes;

/**
 * This is the ActiveQuery class for [[AuthItem]].
 *
 * @see User
 */
class AuthItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AuthItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Roles padre
     * @return $this
     */
    public function rolesPadre()
    {
        return $this->andWhere('[[type]]=1');
    }
}
