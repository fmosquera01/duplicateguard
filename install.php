<?php
declare(strict_types=1);

/**
 * No database schema is required by DuplicateGuard.
 */
function plugin_duplicateguard_install(): bool
{
    return true;
}

function plugin_duplicateguard_uninstall(): bool
{
    return true;
}
