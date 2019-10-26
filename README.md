# PHP-Posts
App en PHP 7 donde se pueden hacer publicaciones tipo Blog Post con autenticacion de usuarios. Manteniendo una **estructura** profesional y manejando los **estándares** de la industria. ([Curso](https://platzi.com/cursos/php/ "Curso") tomado en Platzi)

Temas dados:
- Patrones de diseño  
 -- Front Controller  
 -- MVC  
- [Estándares PSR (PHP-FIG)](https://www.php-fig.org/psr/ "Estándares PSR")  
 -- Autoloading de clases PSR-4  
 -- Manejo de respuestas HTTP PSR-7  
- Gestión de librerías con composer:  
 -- **Eloquent ORM**, Interacción con la Base de Datos  
 -- **Zend/Diactoros**, Implementación de mensajes HTTP PSR-7  
 -- **Aura/Router**, Router HTTP PSR-7  
 -- **Twig**, Motor de plantillas  
 -- **Respect/Validation** (Se usó para hacer validaciones datos en formularios)  
 -- **vlucas/PHPdotenv** (Simular variables de entorno a nivel local)  
</br>

-------------------------------

### ¿Para que se usa un router?
La direccion url debería corresponder con nuestro directorio del proyecto en el servidor. Pero, debido a que estamos usando la programación a orientada a objetos y el patrón MVC, el directorio cambia de estructura y las url de nuestro proyecto no son tan amigables.

Para solucionarlo, se usó el paquete aura/router que a modo práctico: el router escucha todas las peticiones del url en nuestra app y recupera los valores introducidos; y según configuremos el map (nuestro mapa del sitio), el router (dentro del front controller) llama a los respectivos archivos con las clases y métodos, dejando la url mas amigable y permitiendo el acceso solo a los archivos que deseamos.

------------

# Configuración:
Se debe reemplazar ".env_sample" por ".env" y configurar las respectivas variables (Configura la base de datos)
