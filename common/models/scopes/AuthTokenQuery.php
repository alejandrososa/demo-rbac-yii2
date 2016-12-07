<?php

namespace common\models\scopes;

/**
 * This is the ActiveQuery class for [[\common\models\AuthToken]].
 *
 * @see \common\models\AuthToken
 */
class AuthTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\AuthToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\AuthToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
