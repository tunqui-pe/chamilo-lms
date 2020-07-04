### Disk Alert plugin


Este plugin permite habilitar un panel del estado de espacio actual del disco para su campus Chamilo, permite configurar 
alertas de espacio en disco, ideal para VPS e Instancias de AWS.

#### Pasos de activación

- Instalar el plugin.
- En configuración del plugin, activa lo necesario.
- Coloca en plugin en la región de menu_administrator.
- Configura tu CRON.

#### Pasos para configurar el CRON de alerta diaria.

Debes de configurar un CRON diario para tus noticaciones de alerta diaria, de consumo de disco, de
la siguinete forma.

Ejecuta en modo root el crontab

```html
sudo crontab -e
```
Agrega la siguiente línea

```html
15 10 * * * /usr/bin/php7.2 /rutadetuchamilo/plugin/diskalert/cron_send_alerts.php
```
En la línea anterior indica que estara enviando un email a las 10:15 horas, todos los días.

Posteriormente debera resetear los servicios del cron para aplicar este cambio.
```html
/etc/init.d/cron restart
```

**Nota:** Recuerda que debe de estar configurado el correo del administrador y tu servidor de correo funcionando
para el envio de mensajes.