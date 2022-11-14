# Administrador

## Por las configuraciones que agrega en "config/packages/security.yaml" (que se usan en login)  creo el User con:
```
php bin/console make:user

y agrego los atributos que necesito
```


## Para hacer la herencia
- En el caso de la clase padre (User) se agregan por ORM las hijas:
```
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "teacher" = "Teacher",
 *      "admin" = "Admin",
 *      "student" = "Student"
 * })
```
 - Además la clase padre debe ser abstracta:
```
 abstract class User...
```
- Todas las clases hijas deben extender a la clase padre y NO LLEVAN ID
``` 
(ejemplo con ustudiante)
class Student extends User

```

- Hago el crud de las 3 hijas de User, luego saco atributos del form 
``` 
Del AdminType, del StudentType y del TeacherType saco ->add('roles'), en el caso de TeacherType también saco el ->add('course')
```

## Agrego un dashboard 
- Con el siguiente comando (agregará el DashboardController y su index)
```
php bin/console make:controller Dashboard
```

## Login - (luego de haber agregado la encriptación de contraseña)
- Creo el formulario de login, url logout y copnfiguraciones necesarias con...
```
php bin/console make:auth
```
Después de ejecutar el comando anterior
⦁	Pregunta que estilo de autenticación quiero, ingreso “1” para elegir “Login form authenticator .
⦁	Pide un nombre para el authenticartor, el recomendado en el link es “LoginFormAuthenticator”, uso ese. 
⦁	Pide un nombre para el  controller, elijo el por defecto “SecurityController”.
⦁	Si quero generar una ruta para login/logout, elijo “yes”.

- Luego de teminar del paso anterior debo especificar la url de destino luego del inicio de sesisión correcto
```
Para eso ir a src\Security\LoginFormAuthenticator.php
 En la función "onAuthenticationSuccess" buscar la siguiente línea 
    "throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);"
 y cambiarla por 
    "return new RedirectResponse('dashboard');" 
 dónde "dashboard" es el @Route del controller/función de destino luego del inicio de sesisión correcto     
```
## Para que siempre inicie en el dashboard hago el cambio del controller del dashboard y tengo que modificar lo del LoginFormAuthenticator ya que no lo tenía con el name
```
```

## Para la generación de web service de SOAP en este caso solo para crear un usuario (Sea admin, student o teacher)
   Video guía: https://www.youtube.com/watch?v=yWFfCuazfjQ&t=71s&ab_channel=AnderCode

- Además de lo que está a la vista tuve que ejecutar el siguiente comando:
```
composer require econea/nusoap
```
Al ejecutar este comando se nos agrega el archivo "nusoap.php" en la siguiente ruta vendor\econea\nusoap\src\nusoap.php

- Para ver/generar el XML que nos permite hacer uso el web service accedemos (con xampp levantado) de la siguiente manera:
```
http://localhost/administrador/Soap/UserInsertSoap.php
y luego dar clic sobre "WSDL" en "View the WSDL for the service. Click on an operation name to view it's details"

o directamente acceder de la siguiente forma:
http://localhost/administrador/Soap/UserInsertSoap.php?wsdl
```
Me hubiese gustado poner la carpeta Soap dentro de la carpeta src, pero por pruebas que hice sé que, luego al consumir desde los Controller, me genera problemas el require_once por el nivel de los archivos
- Para probarlo se usa
```
SoapUI
```

## Posibilidad de consumir el web service de SOAP por api (ABM sea admin, student o teacher)
   NOTA 1: En el parámetro "userType_input" se deb especificar con que tipo de user se quiere trabajar (los valores posibles para este parámetro son admin, student o teacher). 
   NOTA 2:Voy a poner el puerto 8000 en esta explicación pero puede cambiar (lo asigna [y muestra en consola] el server del proyeto al levantarlo).

- Alta de user mediande soap (método POST)
```
http://localhost:8000/api/new

body:
{
	"dni_input": "13900024",
	"email_input": "email_3@gmail.com",
	"lastname_input": "Lastname_3",
	"name_input": "Name_3",
	"password_input": "12345678",
	"phone_input": "87654321",
	"userType_input": "admin"
}
```
- Modificación de user mediande soap (método POST)
```
http://localhost:8000/api/edit

body:
{
	"id_user_input": 13,
	"dni_input": "1390004",
	"email_input": "email_4@gmail.com",
	"lastname_input": "Lastname_4",
	"name_input": "Name_4",
	"phone_input": "87654324",
	"userType_input": "admin"
}
```
- Baja de user mediande soap (método POST)
```
http://localhost:8000/api/delete

body:
{
	"id_user_input": 13,
	"userType_input": "admin"
}
```
- Cambio de contraseña temporal mediande soap (método POST)
```
http://localhost:8000/api/change/password

body:
{
	"id_user_input": 2,
	"old_password_input": "123456",
  "new_password_input": "12345678",
	"confirm_password_input": "12345678"
}
```