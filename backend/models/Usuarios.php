<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 8/12/16 - 20:35
 */

namespace backend\models;

use yii\helpers\ArrayHelper;

/**
 * Class Usuarios
 */
class Usuarios extends \common\models\User
{
    /**
     * Listado de usuarios para dropdownlist
     * @return mixed
     */
    public static function listadoUsuarios()
    {
        $usuarios = self::find()->all();

        return ArrayHelper::map($usuarios,'id','email');
    }
}