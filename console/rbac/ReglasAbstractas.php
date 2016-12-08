<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 8/12/16 - 12:38
 */

namespace console\rbac;

use yii\rbac\Rule;

/**
 * Class Reglas
 * @package console\rbac
 */
abstract class ReglasAbstractas extends Rule
{
    /**
     * Verifica si el usuario tiene permiso especial
     * Permiso especial es hijo de un permiso
     * @param $permisoEspecial
     * @param $usuario
     * @return bool
     */
    public function usuarioTienePermisoEspecial($permisoEspecial, $usuario)
    {
        if(empty($permisoEspecial)){
            return false;
        }

        $permisos = \Yii::$app->authManager->getPermissionsByUser($usuario);

        foreach ($permisos as $permiso => $valores) {
            if($permiso == $permisoEspecial){
                return true;
            }
        }

        return false;
    }
}