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
