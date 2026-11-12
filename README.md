# DuplicateGuard

Plugin independiente para GLPI 11 que evita tickets duplicados por título.

## Comportamiento inicial

- Se ejecuta en el servidor antes de guardar un ticket.
- Aplica a todos los tickets de GLPI, sin importar si se crean desde la interfaz, FormCreator, API u otra integración que utilice el flujo estándar de creación de GLPI.
- No limita la validación por entidad, categoría, tipo de solicitud, solicitante ni activo relacionado.
- Busca otro ticket no eliminado con el mismo título.
- Bloquea la creación si encuentra un ticket cuyo estado no sea Cerrado. Un ticket Solucionado sigue bloqueando hasta que se cierre.
- Muestra un mensaje con el número del ticket que impide la creación.
- También valida los cambios de título al editar un ticket, para evitar que una edición introduzca un duplicado.
- Ignora el propio ticket durante una edición.

## Comparación de títulos

Se eliminan espacios al inicio y al final del título enviado. La comparación del título existente utiliza la intercalación/collation configurada en la base de datos GLPI.

## Instalación

1. Copiar la carpeta duplicateguard en plugins/.
2. Ir a Configuración > Plugins.
3. Instalar y habilitar DuplicateGuard.

No crea tablas adicionales ni modifica los tickets existentes.

## Requisitos

- GLPI 11.0.x
- PHP 8.2 o superior

## Nota técnica

La validación usa los hooks PRE_ITEM_ADD y PRE_ITEM_UPDATE de GLPI sobre Ticket, cancelando la operación cuando detecta un duplicado.
