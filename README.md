# PHP-Posts
App en PHP 7 donde se pueden hacer publicaciones tipo Blog Post con autenticación de usuarios. El punto no es que la app tenga muchas funcionalidades para el usuario, el punto es **explorar buenas prácticas para desarollarla internamente** y lograr un **código limpio** más un **software fácil de mantener en el tiempo**, con una **estructura** profesional y manejando los **estándares** de la industria. (En parte basada en [curso](https://platzi.com/cursos/php/ "curso") tomado en Platzi).

Temas y conceptos utilizados:
- **Patrones de diseño**  
 -- Front Controller  
 -- MVC  
 -- Dependency Injection  
 -- Singleton  
- **[Estándares PSR (PHP-FIG)](https://www.php-fig.org/psr/ "Estándares PSR")**  
 -- Autoloading de clases PSR-4  
 -- Manejo de respuestas HTTP PSR-7  
- **Principios SOLID**  
 -- Principio de responsabilidad única  
 -- Principio abierto/cerrado  
 -- Principio de substitución de Liskov  
 -- Principio de segregación de interfaces  
 -- Principio de inversión de dependencias  
- **Gestión de librerías con composer**  
 -- **Eloquent ORM**, Interacción con la Base de Datos  
 -- **Zend/Diactoros**, Implementación de mensajes HTTP PSR-7  
 -- **Aura/Router**, Router HTTP PSR-7  
 -- **Twig**, Motor de plantillas  
 -- **Respect/Validation**, Se usó para hacer validaciones datos en formularios  
 -- **vlucas/PHPdotenv**, Variables de entorno  
 -- **league/container**, Contenedor para inyección de dependencias PSR-11  
</br>

------------

# Configuración:
Se debe reemplazar ".env_sample" por ".env" y configurar las respectivas variables (Configura la base de datos)
