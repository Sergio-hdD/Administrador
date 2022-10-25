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
