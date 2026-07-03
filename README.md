# PHP-Posts
App in PHP 7 where you can make Blog Post-like publications with user authentication. It contains several forms, including a contact form that sends emails asynchronously. The goal of this project is exploring the best development practices and achieving clean code and a software that is easy to maintain over time, with a professional structure and following industry standards (Partly based on the [basic](https://platzi.com/cursos/php/ "basic") and [advanced](https://platzi.com/cursos/php-avanzado/ "advanced") PHP courses taken at Platzi). I update the project as I learn new programming techniques.


------------

### Topics and concepts used:
- **Security**  
- **Virtualization**  
- **Creation of a custom command-line interface with app commands**  
- **Unix CRON processes to run commands asynchronously**  
- **Database interaction through an ORM**  
- **HTTP Messages**  
- **Router**  
- **Template engines**  
- **Validations**  
- **Environment variables**  
- **Middlewares**  
- **Debug and XDebug**  
- **Logger**  
- **Migrations**  
- **Sending Emails**  
- **Error handling**  
- **Design patterns**  
 -- Front Controller  
 -- MVC  
 -- Dependency Injection  
 -- Singleton  
- **[PSR Standards (PHP-FIG)](https://www.php-fig.org/psr/ "PSR Standards")**  
 -- PSR-3: Loggers  
 -- PSR-4: Class autoloading  
 -- PSR-7: HTTP response handling  
 -- PSR-15: Middlewares and HTTP Server Request Handler  
- **SOLID Principles**  
 -- Single responsibility principle  
 -- Open/closed principle  
 -- Liskov substitution principle  
 -- Interface segregation principle  
 -- Dependency inversion principle  
- **Library management with composer** 

- **libraries used**  
 -- **Eloquent ORM**, Database interaction  
 -- **Zend/Diactoros**, Implementation of PSR-7 HTTP messages  
 -- **Aura/Router**, PSR-7 HTTP Router  
 -- **Twig**, Template engine  
 -- **Respect/Validation**, Used to validate form data  
 -- **Dotenv**, Environment variables  
 -- **League/container**, PSR-11 dependency injection container  
-- **Harmony**, PSR-15 Middleware Dispatcher  
-- **Whoops**, Browser debug  
-- **Monolog**, PSR-3 Logger  
-- **Phinx**, Database migrations  
-- **Symfony/Console**, Creation of a custom command-line interface with app commands  
-- **Swiftmailer**, Sending Emails 
</br>

------------

# Configuration:
- Replace ".env_sample" with ".env" and configure the respective environment variables (configure the Database).

- Run the Database migrations:
`php vendor/bin/phinx migrate`
