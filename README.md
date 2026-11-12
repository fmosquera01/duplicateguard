# DuplicateGuard

Plugin independiente para GLPI 11 que evita tickets duplicados por título.

## Comportamiento

- Se ejecuta en el servidor antes de guardar un ticket.
- Aplica a todos los tickets de GLPI, sin limitarse a FormCreator, al catálogo de reparaciones ni al plugin HH.
- Funciona con la interfaz de GLPI y con los procesos que utilizan el ciclo estándar de Ticket::add() y Ticket::update().
- No limita la validación por entidad, categoría, tipo de solicitud, solicitante ni activo relacionado.
- Busca otro ticket no eliminado con el mismo título.
- Bloquea la creación o edición si encuentra otro ticket cuyo estado no sea **Cerrado**. Un ticket **Solucionado** sigue bloqueando hasta que se cierre.
- También impide reabrir un ticket si ya existe otro ticket activo con el mismo título.
- Muestra un mensaje con el número del ticket que impide la operación.
- Ignora el propio ticket durante una edición.

## Comparación de títulos

Se eliminan espacios al inicio y al final del título. No se modifican los espacios internos. La comparación del título existente utiliza la intercalación/collation configurada en la base de datos GLPI; por tanto, la sensibilidad a mayúsculas/minúsculas depende de esa configuración.

## Instalación

1. Copiar la carpeta \`duplicateguard\` en \`plugins/\`.
2. Ir a **Configuración > Plugins**.
3. Instalar y habilitar **DuplicateGuard**.

No crea tablas adicionales ni modifica los tickets existentes.

## Requisitos

- GLPI 11.0.x
- PHP 8.2 o superior

## Nota técnica

La validación utiliza los hooks \`PRE_ITEM_ADD\` y \`PRE_ITEM_UPDATE\` de GLPI sobre \`Ticket\`. Cuando detecta un duplicado, cancela la operación en el servidor y muestra el aviso al usuario.
