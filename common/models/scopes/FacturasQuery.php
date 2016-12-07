<?php

namespace common\models\scopes;

/**
 * This is the ActiveQuery class for [[\common\models\Facturas]].
 *
 * @see \common\models\Facturas
 */
class FacturasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Facturas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Facturas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
