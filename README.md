
# Welcome to Api Rest Full!

Hola, me llamo gustavo herrera y he construido el api rest full para symfony, usando mvc y colocando el sanctum para la proteccion de rutas.

  
  

# Requerimientos

Los requerimientos para correr el sistema es un servidor con la version **php 8.2** y una base de datos mysql de versión 8.

Dentro de la carpeta public se encuentra la carpeta script llamada **script-database** alli se encuentra la carpeta del sql de la base de datos. El archivo se llama **api_symfony_test_db.sql**

  
  

# Instrucciones para levantar

  

1. Se debe verificar que la base de datos este levantada y verificar los parametros de conexión en el archivo .env donde encontraremos el siguiente dato:

> DATABASE_URL="mysql://usuario:password@127.0.0.1:3306/api_symfony_test_db"

1. Dentro de la carpeta raiz del proyecto se debe correr php con el siguiente comando: ```symfony server:start``` este comando levantara el proyecto. Debe mostrar en el terminal la siguiente url:

http://127.0.0.1:8000/

  

## Pruebas

Se encuentra en la carpeta public la colleccion del postman para realizar las pruebas. La carpeta se llama postman-collection.

- Se debe primero realizar la autenticacion del usuario para recibir el token para poder realizar las pruebas de cada endpoint agregado al collection. El dato del usuario a logear es: **username:  app@test.com**
  **password: 123456789**

