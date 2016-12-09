Api RESTful con autorización
============================

**¿Qué es Rest?**

REST, REpresentational State Transfer, es un tipo de arquitectura de desarrollo web que se apoya totalmente en el estándar HTTP.

REST nos permite crear servicios y aplicaciones que pueden ser usadas por cualquier dispositivo o cliente que entienda HTTP, por lo que es increíblemente más simple y convencional que otras alternativas que se han usado en los últimos diez años como SOAP y XML-RPC.

REST se definió en el 2000 por Roy Fielding, coautor principal también de la especificación HTTP. Podríamos considerar REST como un framework para construir aplicaciones web respetando HTTP.

Por lo tanto REST es el tipo de arquitectura más natural y estándar para crear APIs para servicios orientados a Internet.

Existen tres niveles de calidad a la hora de aplicar REST en el desarrollo de una aplicación web y más concretamente una API que se recogen en un modelo llamado Richardson Maturity Model en honor al tipo que lo estableció, Leonard Richardson padre de la arquitectura orientada a recursos. Estos niveles son:

1.- Uso correcto de URIs
2.- Uso correcto de HTTP.
3.- Implementar Hypermedia.

Además de estas tres reglas, nunca se debe guardar estado en el servidor, toda la información que se requiere para mostrar la información que se solicita debe estar en la consulta por parte del cliente.

Al no guardar estado, REST nos da mucho juego, ya que podemos escalar mejor sin tener que preocuparnos de temas como el almacenamiento de variables de sesión e incluso, podemos jugar con distintas tecnologías para servir determinadas partes o recursos de una misma API.

**Servicios web RESTful**

Así pues, teniendo claros estos conceptos y cómo se relacionan con el protocolo HTTP, y sabiendo que un servicio web RESTful hace referencia a un servicio web que implementa la arquitectura REST, podemos ya dar una definición concisa, lo cual nos dejará claro cómo tenemos que implementarlo en nuestras aplicaciones. Un servicio web RESTful contiene lo siguiente:

URI del recurso. Por ejemplo: http://api.servicio.com/recursos/casas/1 (esto nos daría acceso al recurso “Factura” con el ID “1”)
El tipo de la representación de dicho recurso. Por ejemplo, podemos devolver en nuestra cabecera “Content-type: application/json”, por lo que el cliente sabrá que el contenido de la respuesta es una cadena en formato JSON, y podrá procesarla como prefiera. El tipo es arbitrario, siendo los más comunes JSON, XML y TXT.
Operaciones soportadas: HTTP define varios tipos de operaciones (verbos) [3], que pueden ser GET, PUT, POST, DELETE, PURGE, entre otros. Es importante saber para que están pensados cada verbo, de modo que sean utilizados correctamente por los clientes.
Hipervínculos: por último, nuestra respuesta puede incluir hipervínculos hacia otras acciones que podamos realizar sobre los recursos. Normalmente se incluyen en el mismo contenido de la respuesta, así si por ejemplo, nuestra respuesta es un objeto en JSON, podemos añadir una propiedad más con los hipervínculos a las acciones que admite el objeto.
En la imagen se muestra un ejemplo de una petición al servicio web de GitHub. No se muestra, pero en el contenido de la respuesta no se incluían hipervínculos a otras acciones, por lo tanto no es una servicio web RESTful completo.

hemos visto cómo tenemos que hacer uso de este protocolo para considerar un servicio web RESTful completo. Todo esto nos dará la base teórica que necesitamos para implementar y consumir servicios web RESTful.

|Usuario| Token|
|---|---|
|admin	|l1STFriemVGnSXEGkpNpWcY5XnKGBmSs|
|particular	|I2LowN6s9m23JtVIY5KAojTu5-t2Nk91|
|vendedor	|tFgX3AKgWoTSJNApcRGf9FZDdiuLrSpA|
|empleado	|_RymZHzH9az5f-vrJmmoxOepuyhfT4yb|

Configuración Modulo API
========================

Nuestra api es un modulo que se encuentra en
    
    /var/www/html/appdemoyii2/frontend/modules/api
    
Para añadirlo a nuestra aplicación de yii solo debemos agregarlo en el config

    'modules' => [
        ...
        'api' => [
            'class' => 'frontend\modules\api\Modulo',
        ],
        ...
    ],
    'components' => [
        ...
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'pluralize' => false, 'controller' => 'api/default'],
                ['class' => 'yii\rest\UrlRule', 'pluralize' => false, 'controller' => 'api/factura'],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ]
        ...
    ]

La parte divertida, hacer llamadas a la api
-------------------------------------------

Para hacer las llamadas a nuestra api, 