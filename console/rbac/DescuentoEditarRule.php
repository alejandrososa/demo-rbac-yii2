<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 6/12/16 - 22:01
 */

namespace console\rbac;


use yii\rbac\Rule;

/**
 * Comprueba si el propietario ID coincide usuario pasado a través de params
 */
class DescuentoEditarRule extends Rule {

    public $name = 'editarDescuento';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params) {
        return isset($params['descuento']) ? $params['descuento']->user_id == $user : false;
    }

}