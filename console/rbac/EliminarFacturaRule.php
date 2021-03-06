<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 6/12/16 - 21:54
 */

namespace console\rbac;
use console\rbac\ReglasAbstractas;

/**
 * Comprueba si el propietario ID coincide usuario pasado a través de params
 */
class EliminarFacturaRule extends ReglasAbstractas {

    public $name = 'eliminarFacturas';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params) {
        return $this->usuarioTienePermisoEspecial($this->name, $user);
    }
}