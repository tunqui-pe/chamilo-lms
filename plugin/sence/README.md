Conecto SENCE plugin
===

La Integración Chamilo API SENCE se realiza con el fin de monitorear el avance y ejecución de los cursos modalidad e-learning, los Organismos Técnicos de Capacitación OTEC deben informar el inicio y fin de sesión de los alumnos a través de sus respectivas plataformas LMS o Aulas Virtuales, para Chile.

Plugin que conecta el servicio SENCE con la plataforma de e-learning Chamilo
---

*Integra*:

- Conexiones POST
- Formularios para editar datos

*Instrucciones*:

- Instalar plugin
- En configuración del plugin: Activar Herramienta SENCE -> SI
- Ingresa los datos necesarios para la integración y luego Guardar.

Versión 1.5 (Update 11-08-20)
---
- En esta versión se agrego la opción de Multi ID de Acción, permitiendo que en un solo curso o sesión puede haber diferentes usuarios
con un ID de acción diferente.

- Actualización de versión 1.0 a versión 1.5 si tiene el plugin ya en uso deberá realizar la siguiente modificación:
```sql
ALTER TABLE plugin_sence_courses ADD action_id INT NULL DEFAULT NULL AFTER training_line;
ALTER TABLE plugin_sence_logs ADD action_id INT NULL DEFAULT NULL AFTER code_course;
ALTER TABLE plugin_sence_users_login ADD action_id INT NULL DEFAULT NULL AFTER code_course;
```
 
 
