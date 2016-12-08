<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 8/12/16 - 21:49
 */

namespace backend\models;

use common\models\AuthItem;
use yii\helpers\ArrayHelper;

/**
 * Class Roles
 * @package backend\models
 */
class Roles extends AuthItem
{
    /**
     * Listado de roles
     * @return array
     */
    public static function listadoRoles()
    {
        $usuarios = self::find()->rolesPadre()->all();

        return ArrayHelper::map($usuarios,'name','name');
    }
}