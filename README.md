# Sistema de Recordatorio de Correo Electrónico

Este sistema envía recordatorios por correo electrónico a los usuarios. Los correos electrónicos se envían en función de un `next_send_time` almacenado en una base de datos.

## Funcionamiento

El sistema funciona de la siguiente manera:

1. Selecciona todos los correos electrónicos pendientes cuyo `next_send_time` sea mayor o igual a la hora actual y menor que la hora en una hora.
2. Para cada correo electrónico seleccionado, envía el correo electrónico y calcula el próximo tiempo de envío sumando la frecuencia al `next_send_time` actual.
3. Actualiza el `next_send_time` en la base de datos con el nuevo tiempo calculado.

## Configuración

Para configurar el sistema, debes establecer la información de la base de datos y la configuración de PHPMailer en `config.php`.

## Uso

Para usar el sistema, simplemente ejecuta `php send_emails.php` en la línea de comandos.

## Automatización

Para automatizar el envío de correos electrónicos, puedes asignar un cronjob a `send_emails.php`. Esto hará que el script se ejecute automáticamente a intervalos regulares.

Por ejemplo, para ejecutar el script cada hora, puedes agregar la siguiente línea a tu crontab:
Alternativamente, si prefieres ejecutar el script a través de un servidor web, puedes usar `wget` en tu cronjob. Por ejemplo:

```bash
0 * * * * /usr/bin/php /path/to/your/script/send_emails.php

o

```bash
0 * * * * wget -O - -q -t 1 http://yourwebsite.com/path/to/your/script/send_emails.php