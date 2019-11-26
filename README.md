# PHP-Posts
App en PHP 7 donde se pueden hacer publicaciones tipo Blog Post con autenticación de usuarios, contiene varios formularios incluyendo uno de contacto donde se realiza el envío de Email de forma asíncrona. El punto no es que la app tenga muchas funcionalidades para el usuario, es **explorar buenas prácticas para desarollarla internamente** y lograr un **código limpio** más un **software fácil de mantener en el tiempo**, con una **estructura** profesional y manejando los **estándares** de la industria (En parte basada en el curso [básico](https://platzi.com/cursos/php/ "básico") y [avanzado](https://platzi.com/cursos/php-avanzado/ "avanzado") de php tomados en Platzi). Actualizo el proyecto mientras aprendo nuevas ténicas de programación.
### [Ver demo online del proyecto](http://felix-platziphp.herokuapp.com "Ver demo online del proyecto")  



------------

### Temas y conceptos utilizados:
- **Seguridad**  
- **Virtualización**  
- **Creación de intefaz de linea de comandos personalizada con comandos de la app**  
- **Procesos CRON Unix para ejecutar comandos asíncronamente**  
- **Interacción con la Base de Datos mediante un ORM**  
- **Mensajes HTTP**  
- **Router**  
- **Motores de plantillas**  
- **Validaciones**  
- **Variables de entorno**  
- **Middlewares**  
- **Debug y XDebug**  
- **Logger**  
- **Migraciones**  
- **Envío de Emails**  
- **Manejo de errores**  
- **Patrones de diseño**  
 -- Front Controller  
 -- MVC  
 -- Dependency Injection  
 -- Singleton  
- **[Estándares PSR (PHP-FIG)](https://www.php-fig.org/psr/ "Estándares PSR")**  
 -- PSR-3: Loggers  
 -- PSR-4: Autoloading de clases  
 -- PSR-7: Manejo de respuestas HTTP  
 -- PSR-15: Middlewares y Server Request Handler HTTP  
- **Principios SOLID**  
 -- Principio de responsabilidad única  
 -- Principio abierto/cerrado  
 -- Principio de substitución de Liskov  
 -- Principio de segregación de interfaces  
 -- Principio de inversión de dependencias  
- **Gestión de librerías con composer** 

- **librerías utilizadas**  
 -- **Eloquent ORM**, Interacción con la Base de Datos  
 -- **Zend/Diactoros**, Implementación de mensajes HTTP PSR-7  
 -- **Aura/Router**, Router HTTP PSR-7  
 -- **Twig**, Motor de plantillas  
 -- **Respect/Validation**, Se usó para hacer validaciones datos en formularios  
 -- **Dotenv**, Variables de entorno  
 -- **League/container**, Contenedor para inyección de dependencias PSR-11  
-- **Harmony**, Middleware Dispatcher PSR-15  
-- **Whoops**, Debug en navegador  
-- **Monolog**, Logger PSR-3  
-- **Phinx**, Migraciones de bases de datos  
-- **Symfony/Console**, Creación de intefaz de linea de comandos personalizada con comandos de la app
-- **Swiftmailer**, Envío de Emails 
</br>

------------

# Configuración:
- Reemplazar ".env_sample" por ".env" y configurar las respectivas variables de entorno (configurar la Base de Datos).

- Ejecutar las migraciones de  la Base de Datos:
`php vendor/bin/phinx migrate`
