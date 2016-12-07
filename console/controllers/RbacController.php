<?php
/**
 * Creado para PhpStorm.
 * Desarrollador: grace
 * Fecha: 6/12/16 - 21:49
 */

namespace console\controllers;

namespace console\controllers;
use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\User;
use common\models\AuthAssignment;
use console\rbac\ArticuloPropietarioRule;
use console\rbac\ArticuloEmpleadoRule;
use console\rbac\ArticuloCrearRule;
use console\rbac\DescuentoCrearRule;
use console\rbac\DescuentoEditarRule;
/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller{
    //tipos de usuario
    const USUARIO_ADMIN = 'admin';
    const USUARIO_PARTICULAR = 'particular';
    const USUARIO_VENDEDOR = 'vendedor';
    const USUARIO_EMPRESA = 'empresa';
    const USUARIO_INVERSOR = 'inversor';
    const USUARIO_EMPLEADO = 'empleado';

    //tipos de permisos
    const P_CREAR_ARTICULOS = 'crearArticulos';
    const P_CREAR_DESCUENTOS = 'crearDescuentos';
    const P_EDITAR_ARTICULOS_PROPIOS = 'editarArticulosPropios';
    const P_EDITAR_ARTICULOS_AJENOS = 'editarArticulosAjenos';
    const P_EDITAR_DESCUENTOS = 'editarDescuentos';
    //administradores
    private $administradores = [
        'alesjohnson@hotmail.com'
    ];
    //demos particulares
    private $particulares = [
        'demo1@correo.com',
        'demo2@correo.com'
    ];
    /**
     * @var array Array de roles: nombre y descripcion
     */
    private $roles = [
        self::USUARIO_ADMIN => 'Puede acceder a todo',
        self::USUARIO_PARTICULAR => 'Puede acceder panel administracion de un particular',
        self::USUARIO_VENDEDOR => 'Puede acceder panel administracion de vendedor',
        self::USUARIO_EMPRESA => 'Puede acceder panel administracion de empresa',
        self::USUARIO_INVERSOR => 'Puede acceder estadisticas administracion',
        self::USUARIO_EMPLEADO => 'Puede acceder panel administracion de empleado'
    ];
    /**
     * @var array Array de reglas: classnames
     */
    private $reglas = [
        ArticuloCrearRule::class,
        ArticuloPropietarioRule::class,
        ArticuloEmpleadoRule::class,
        DescuentoCrearRule::class,
        DescuentoEditarRule::class,
    ];
    /**
     * @var array Array de permisos
     */
    private $permissions = [
        self::P_CREAR_ARTICULOS => [
            'description' => 'Permiso para crear articulos',
            'ruleName' => 'crearArticulo',
        ],
        self::P_CREAR_DESCUENTOS => [
            'description' => 'Permiso para crear descuentos',
            'ruleName' => 'crearDescuentos',
        ],
        self::P_EDITAR_ARTICULOS_PROPIOS => [
            'description' => 'Permiso para actualizar articulos propios',
            'ruleName' => 'esArticuloPropietario',
        ],
        self::P_EDITAR_ARTICULOS_AJENOS => [
            'description' => 'Permiso para actualizar articulos de otros usuarios',
            'ruleName' => 'esArticuloAjeno',
        ],
        self::P_EDITAR_DESCUENTOS => [
            'description' => 'Permiso para actualizar descuentos',
            'ruleName' => 'editarDescuento',
        ],
    ];
    /**
     * @var array Array de permisos asignados a un rol
     */
    private $rolPermisos = [
        self::USUARIO_PARTICULAR    => [
            self::P_CREAR_ARTICULOS,
            self::P_EDITAR_ARTICULOS_PROPIOS,
        ],
        self::USUARIO_VENDEDOR      => [
            self::P_CREAR_ARTICULOS,
            self::P_EDITAR_ARTICULOS_PROPIOS,
        ],
        self::USUARIO_EMPRESA => [
            self::P_EDITAR_ARTICULOS_PROPIOS,
            self::P_CREAR_DESCUENTOS,
            self::P_CREAR_ARTICULOS
        ],
        self::USUARIO_EMPLEADO => [
            self::P_EDITAR_ARTICULOS_AJENOS,
            self::P_EDITAR_DESCUENTOS,
        ]
    ];
    /**
     * Se ejecutará RefrescarRoles, RefrescarReglas, RefrescarPermisos, RefrescarRolesPermisos
     */
    public function actionIniciar() {
//        exec('cd c:/xampp/htdocs/appab/');
//        exec('php c:/xampp/htdocs/appab/yii migrate --migrationPath=@yii/rbac/migrations');
        $this->actionRefrescarRoles();
        $this->actionRefrescarReglas();
        $this->actionRefrescarPermisos();
        $this->actionRefrescarRolesPermisos();
        //asignar administradores
        foreach($this->administradores as $correo){
            $this->actionAsignarAdmin($correo);
        }
        //asignar particulares
        foreach($this->particulares as $correo){
            $this->actionAsignarParticular($correo);
        }
    }
    public function actionDemo()
    {
        $auth = Yii::$app->authManager;
        // add "createPost" permission
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);
        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);
        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);
        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);
        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
    }
    /**
     * Va a crear/editar roles
     */
    public function actionRefrescarRoles() {
        $auth = Yii::$app->authManager;
        foreach ($this->roles as $roleName => $roleDesc) {
            $role = $auth->getRole($roleName);
            if (!$role) {
                $role = $auth->createRole($roleName);
                $role->description = $roleDesc;
                $auth->add($role);
            } else {
                $role = $auth->getRole($roleName);
                $role->description = $roleDesc;
                $auth->update($roleName, $role);
            }
        }
    }
    /**
     * Va a crear/editar reglas
     */
    public function actionRefrescarReglas() {
        $auth = Yii::$app->authManager;
        foreach ($this->reglas as $ruleClass) {
            /* @var $ruleObject \yii\rbac\Rule */
            $ruleObject = new $ruleClass;
            $rule = $auth->getRule($ruleObject->name);
            if (!$rule) {
                $auth->add($ruleObject);
            } else {
                $auth->update($ruleObject->name, $ruleObject);
            }
        }
    }
    /**
     * Va a crear/editar permisos
     */
    public function actionRefrescarPermisos() {
        $auth = Yii::$app->authManager;
        foreach ($this->permissions as $permissionName => $permissionData) {
            $permission = $auth->getPermission($permissionName);
            if (!$permission) {
                $permission = $auth->createPermission($permissionName);
                $permission->description = $permissionData['description'];
                $permission->ruleName = !empty($permissionData['ruleName']) ?
                    $permissionData['ruleName'] : null;
                $auth->add($permission);
            } else {
                $permission->description = $permissionData['description'];
                $permission->ruleName = !empty($permissionData['ruleName']) ?
                    $permissionData['ruleName'] : null;
                $auth->update($permission->name, $permission);
            }
        }
    }
    /**
     * Refrescará las relaciones entre roles y permisos
     */
    public function actionRefrescarRolesPermisos() {
        $auth = Yii::$app->authManager;
        foreach ($this->rolPermisos as $roleName => $rolPermisos) {
            $role = $auth->getRole($roleName);
            $auth->removeChildren($role);
            foreach ($rolPermisos as $permissionName) {
                $permission = $auth->getPermission($permissionName);
                $auth->addChild($role, $permission);
            }
        }
    }
    /**
     * Asignar rol de administrador a un usuario
     * @param string $correo
     */
    public function actionAsignarAdmin($correo) {
        $this->gestionarRol(self::USUARIO_ADMIN, $correo, true);
    }
    /**
     * Revoca rol admin de un usuario
     * @param string $correo
     */
    public function actionRevocarAdmin($correo) {
        $this->gestionarRol(self::USUARIO_ADMIN, $correo, false);
    }
    /**
     * Asignar rol de inversor a un usuario
     * @param string $correo
     */
    public function actionAsignarInversor($correo) {
        $this->gestionarRol(self::USUARIO_INVERSOR, $correo, true);
    }
    /**
     * Revoca rol inversor de un usuario
     * @param string $correo
     */
    public function actionRevocarInversor($correo) {
        $this->gestionarRol(self::USUARIO_INVERSOR, $correo, false);
    }
    /**
     * Asignar rol de empleado a un usuario
     * @param string $correo
     */
    public function actionAsignarEmpleado($correo) {
        $this->gestionarRol(self::USUARIO_EMPLEADO, $correo, true);
    }
    /**
     * Revoca rol empleado de un usuario
     * @param string $correo
     */
    public function actionRevocarEmpleado($correo) {
        $this->gestionarRol(self::USUARIO_EMPLEADO, $correo, false);
    }
    /**
     * Asignar rol de particular a un usuario
     * @param string $correo
     */
    public function actionAsignarParticular($correo) {
        $this->gestionarRol(self::USUARIO_PARTICULAR, $correo, true);
    }
    /**
     * Revoca rol particular de un usuario
     * @param string $correo
     */
    public function actionRevocarParticular($correo) {
        $this->gestionarRol(self::USUARIO_PARTICULAR, $correo, false);
    }
    /**
     * Asignar rol de vendedor a un usuario
     * @param string $correo
     */
    public function actionAsignarVendedor($correo) {
        $this->gestionarRol(self::USUARIO_VENDEDOR, $correo, true);
    }
    /**
     * Revoca rol vendedor de un usuario
     * @param string $correo
     */
    public function actionRevocarVendedor($correo) {
        $this->gestionarRol(self::USUARIO_VENDEDOR, $correo, false);
    }
    /**
     * Gestiona rol (administrador, particular, vendedor, empresa, inversor, empleado) de un usuario
     * @param string $roleName
     * @param string $correo
     * @param boolean $assign
     */
    public function gestionarRol($roleName, $correo, $assign = true) {
        $usuario = User::findOne(['email' => $correo]);
        if (!$usuario) {
            $this->stdout("No se ha encontrado usuario con el correo '" . $correo . "'\n", Console::FG_RED);
            return;
        }
        if ($this->tienePermisosUsuario($roleName, $usuario->id) && $assign) {
            $this->stdout("El usuario '" . $usuario->username . "' ya tenia asignados los permisos de '" . $roleName . "'\n", Console::FG_RED);
            return;
        }
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        if ($assign) {
            $auth->assign($role, $usuario->id);
            $this->stdout("Rol fue asignado\n", Console::FG_GREEN);
        } else {
            $auth->revoke($role, $usuario->id);
            $this->stdout("Rol fue revocado\n", Console::FG_GREEN);
        }
    }
    /**
     * Verifica si un usuario ya tiene asignado un rol
     * @param $rol
     * @param $usuario
     * @return bool
     */
    private function tienePermisosUsuario($rol, $usuario) {
        return AuthAssignment::find()->where('item_name = :rol and user_id = :usuario',
            [':rol' => $rol, ':usuario' => $usuario])->exists();
    }
}