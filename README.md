# PHP-Posts
Una simple app donde se pueden hacer publicaciones, con autenticacion previa.

Re-practicando patrones de diseño como el Front Controller y MVC, y aprendiendo implementacion de estándares PSR, como el autoloading de clases PSR-4, el manejo de respuestas HTTP PSR-7, y aprendiendo gestión de librerías con composer, se usaron las siguientes librerías:

Eloquent, illuminate/database (Interacción con la Base de Datos)<br/>
Diactoros, zendframework/zend-diactoros (Implementación de mensajes HTTP PSR-7)<br/>
Aura Router, aura/router (Router HTTP PSR-7)<br/>
Twig, twig/twig (El gestor de plantillas)<br/>
respect/validation (Se usó para hacer validaciones datos en formularios)<br/>
vlucas/phpdotenv (Simular variables de entorno a nivel local)<br/>

-------------------------------

¿Para que se usa un router?
La direccion url debería corresponder con nuestro directorio del proyecto en el servidor. 
Pero, debido a que estamos usando la programación a objetos y el patrón MVC, el directorio 
cambia de estructura y las url de nuestro proyecto no son tan amigables.

Para solucionarlo, se usó el paquete aura/router que a modo práctico: el router escucha todas las 
peticiones del url en nuestra app y recupera los valores introducidos; y según configuremos el 
map (nuestro mapa del sitio), el router (dentro del front controller) llama a los respectivos 
archivos con las clases y métodos, dejando la url mas amigable y permitiendo el acceso solo 
a los archivos que deseamos.