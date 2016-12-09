CONTROL DE ACCESOS BASADO EN ROLES (RBAC)
=========================================

Modulo de seguridad para controlar el acceso de usuarios a tareas que normalmente están restringidas al superusuario.

El modelo RBAC consta los siguientes elementos:

**Autorización:** definición de qué es lo que un usuario específico puede hacer dentro de una aplicación, es decir a qué información y operaciones tiene acceso. 

**Elemento de autorización:** son operaciones dependientes de una autorización.

**Rol**: una identidad especial para ejecutar aplicaciones con privilegios. Sólo los usuarios asignados pueden asumir la identidad especial. 

**Reglas**: validación de un elemento de autorización sobre el objeto (facturas) que se requiere gestionar (editar, ver, crear, eliminar)

|Autorización|Tipo|Descripción|Regla|Data|
|---|---|---|---|---|
|admin|	1	|Puede acceder a todo	|(no definido)	|(no definido)|	  
|empleado|	1	|Puede acceder panel administracion de empleado	|(no definido)	|(no definido)|	  
|empresa|	1	|Puede acceder panel administracion de empresa	|(no definido)	|(no definido)|	  
|inversor|	1	|Puede acceder estadisticas administracion	|(no definido)	|(no definido)|	  
|particular|	1	|Puede acceder panel administracion de un particular	|(no definido)|	(no definido)|	  
|vendedor|	1	|Puede acceder panel administracion de vendedor	|(no definido)	|(no definido)|
|crearFacturas|	2	|Permiso para crear facturas	|crearFacturas	|(no definido)	  
|editarFacturasAjenas|	2	|Permiso para actualizar facturas de otros usuarios	|editarFacturasAjenas	|(no definido)|	  
|editarFacturasPropias|	2	|Permiso para actualizar facturas propias	|editarFacturasPropias	|(no definido)|	  
|eliminarFacturas|	2	|Permiso para eliminar facturas	|eliminarFacturas	|(no definido)|	  


Vamos a crear usuarios los cuales tendrán un rol en nuestra aplicación demo, por favor no crear nada en base de datos, continúa leyendo.

|Usuario|Rol|Descripción|
|---|---|---|
|admin@correo.com | admin | tiene autorización a todas las operaciones de la aplicación|
|empleado@correo.com | empleado | tiene autorización a acceder a las facturas y solo puede ejecutar operaciones de (crear, ver, editar propias facturas, editar facturas ajenas)|
|vendedor@correo.com | vendedor | tiene autorización a acceder a las facturas y solo puede ejecutar operaciones de (crear, ver, editar propias facturas)|
|particular@correo.com | particular | tiene autorización a acceder para ver la lista de facturas|
|'@' | usuario registrado | tiene autorización solo a acceder a la administración|
|'?' | usuario desconocido | no tiene autorización a acceder a la administración|


Autorizaciones
--------------

|Operación|Desconocido|Registrado|Particular|Vendedor|Empleado|Admin|
|---|---|---|---|---|---|---|
|Ver facturas|**X**|**OK**|**OK**|**OK**|**OK**|**OK**|
|Crear factura|**X**|**X**|**X**|**OK**|**OK**|**OK**|
|Ver detalle factura|**X**|**X**|**X**|**OK**|**OK**|**OK**|
|Editar factura propias|**X**|**X**|**X**|**OK**|**OK**|**OK**|
|Editar factura ajenas|**X**|**X**|**X**|**X**|**OK**|**OK**|
|Eliminar factura|**X**|**X**|**X**|**X**|**X**|**OK**|
|Gestionar autorizaciones|**X**|**X**|**X**|**X**|**X**|**OK**|
|Gestionar usuarios|**X**|**X**|**X**|**X**|**X**|**OK**|

Veamos el ejemplo con detenimiento. A los usuarios no logueados (visitantes) se les permite la acción INDEX y se deniegan todas las demás, a los logueados (usuarios registrados) se les permite las acciones INDEX y VIEW, mientras que se les deniega el resto de acciones. A los usuarios que tengan el elemento de autenticación "adminFinanciero" asignado (directa o indirectamente) tendrán acceso a las acciones INDEX, VIEW, CREATE, UPDATE, ADMIN y DELETE; y se les deniega el resto de acciones (si las hubiera).

Pues con esto, voy a dar por terminado este pequeño artículo introductorio al RBAC.

Configuración Modulo RBAC
=========================

Nuestra api es un modulo que se encuentra en
    
    /var/www/html/appdemoyii2/backend/modules/rbac
    
Para añadirlo a nuestra aplicación de yii solo debemos agregarlo en el config

    'modules' => [
        ...
        'rbac' => [
            'class' => 'backend\modules\rbac\Modulo',
        ],
        ...
    ],
    'components' => [
        ...
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY, // use temporary redirection instead of permanent
            ],
            'rules' => [
                //modulo facturas
                '<module:(facturas)>'=>'<module>/facturas/index',
                '<module:(facturas)>/<controller:\w+>'=>'<module>/facturas/index',
                '<module:(facturas)>/<action:\w+>/<id:\d+>'=>'<module>/facturas/<action>',

                //modulo cuentas
                '<module:(cuentas)>'=>'<module>/usuarios/index',
                '<module:(cuentas)>/<controller:\w+>'=>'<module>/usuarios/index',
                '<module:(cuentas)>/<action:\w+>/<id:\d+>'=>'<module>/usuarios/<action>',
            ],
        ],
        ...
    ]


La parte divertida, ejecutar comandos en consola
------------------------------------------------

Para iniciar nuestro demo debemos crear una base de datos, opcionar una para los tests unitarios y funcionales
    
    create database demoyii2advanced charset utf8;
    create database demoyii2advanced_tests charset utf8;

En nuestra consola nos posicionamos en el root de nuestra aplicación e inicializamos nuestro entorno de trabajo de desarrollo
    
    cd /var/www/html/appdemoyii2
    php init --env=Development --overwrite=All      (DESARROLLO)
    php init --env=Production --overwrite=All       (PRODUCCIÓN)
    
Puedes cambiar la base de datos, usuario, clave u otra configuración en los entornos de desarrollo y producción a tu gusto en

    /var/www/html/appdemoyii2/environments
    
Ahora que tenemos configurado nuestro entorno, llega la hora de crear los modelos en la base de datos

    php ./yii migrate
  
En nuestra base de datos tendremos las siguientes tablas: migration, facturas, authtoken, auth_rule, auth_item, auth_item_child, auth_assignment.

Ya no tienes que preocuparte por crear los modelos en Yii, pues lo he hecho por ti :)

**User:** (user) Tabla que contiene los usuarios de la aplicación.
**AuthToken:** Tabla que contiene los token de autorización para cada usuario con el cual puede acceder a la aplicación.
**Facturas:** (facturas) Tabla que contiene las facturas que gestionan los usuarios.
**AuthItem:** (auth_item) Tabla que contiene los elementos de autorización.
**AuthItemChild:** Tabla que contiene los elementos de autorización padre-hijo.
**AuthAssignment:** Tabla que contiene la signacion usuario-autorizacion.
**AuthRule:** Tabla que contiene la reglas de validación para los elementos de autorización.

Ya tenemos todas las tablas que necesitamos para nuestro demo, solo falta una cosa, inicializar nuestro RBAC, es muy sencillo, ya también lo he hecho por ti :)
    
    php ./yii rbac/iniciar
    
Puedes ver todos las acciones disponibles y parametros requeridos que tiene el comando rbac 

    php ./yii rbac --help                   (detalles de todas las operaciones del comando rbac)
    php ./yii help rbac/asignar-admin       (detalle de la operaciones de un sub comando rbac)


Ahora puedes probar la aplicación accediendo a la parte backend:
    
    http://localhost/appdemoyii2/backend/web/site/login
    
|Usuario|Clave|
|---|---|
|particular|123456|
|empleado|123456|
|vendedor|123456|
|admin|123456|

Me imagino que a esta altura de la dinamica te habrás dado cuenta que cuando accedes con todos los usuarios ven lo mismo y no existe ninguna opción de Facturas o Administración en el menú, esto es porque falta asignarle los roles a nuestros usuarios.
Vamos a ellos
        
Ejemplo de como asignar un rol por consola a un usuario

    php ./yii rbac/asignar-admin juanpablo@correo.com
    php ./yii rbac/revocar-admin antoniodelamasa@correo.com
    php ./yii rbac/asignar-empleado franciscoiglesias@correo.com
    
En nuestra tabla de usuarios tenemos los siguientes usuarios registrados: admin, particular, vendedor, empleado. A los cuales vamos asignar un rol a cada uno

    php ./yii rbac/asignar-particular particular@correo.com
    php ./yii rbac/asignar-vendedor vendedor@correo.com
    php ./yii rbac/asignar-empleado empleado@correo.com
    php ./yii rbac/asignar-admin admin@correo.com



Espero que sirva a más de uno para aclarar ideas, pero con que le sirva a uno sólo me doy por satisfecho.