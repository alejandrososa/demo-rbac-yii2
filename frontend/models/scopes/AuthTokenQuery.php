<?php

namespace app\models\scopes;

/**
 * This is the ActiveQuery class for [[\app\models\AuthToken]].
 *
 * @see \app\models\AuthToken
 */
class AuthTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\AuthToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\AuthToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
