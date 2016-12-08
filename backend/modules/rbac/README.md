CONTROL DE ACCESOS BASADO EN ROLES (RBAC)
=========================================

Modulo de seguridad para controlar el acceso de usuarios a tareas que normalmente están restringidas al superusuario.

El modelo RBAC consta los siguientes elementos:

**Autorización:** definición de qué es lo que un usuario específico puede hacer dentro de una aplicación, es decir a qué información y operaciones tiene acceso. 

**Elemento de autorización:**

**Rol**: una identidad especial para ejecutar aplicaciones con privilegios. Sólo los usuarios asignados pueden asumir la identidad especial. 


'itemTable'=>'AuthItem', // Tabla que contiene los elementos de autorizacion
        'itemChildTable'=>'AuthItemChild', // Tabla que contiene los elementos padre-hijo
        'assignmentTable'=>'AuthAssignment', // Tabla que contiene la signacion usuario-autorizacion


|Usuario|Rol|Descripción|
|---|---|---|
|admin@correo.com|admin|tiene autorización a todas las operaciones de la aplicación|
|empleado@correo.com|empleado|tiene autorización a acceder a las facturas y solo puede ejecutar operaciones de (crear, ver, editar propias facturas, editar facturas ajenas)|
|vendedor@correo.com|vendedor|tiene autorización a acceder a las facturas y solo puede ejecutar operaciones de (crear, ver, editar propias facturas)|
|particular@correo.com|particular|tiene autorización a acceder para ver la lista de facturas|
|'@'|usuario registrado|tiene autorización solo a acceder a la administración|
|'?'|usuario desconocido|no tiene autorización a acceder a la administración|
