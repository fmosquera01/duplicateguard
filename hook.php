<?php
declare(strict_types=1);

/**
 * Installation and uninstallation hooks for DuplicateGuard.
 *
 * No database schema is required: the plugin validates GLPI tickets
 * using the existing Ticket table.
 */
function plugin_duplicateguard_install(): bool
{
    return true;
}

function plugin_duplicateguard_uninstall(): bool
{
    return true;
}
