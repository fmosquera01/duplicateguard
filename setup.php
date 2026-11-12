<?php
declare(strict_types=1);

use Glpi\Plugin\Hooks;

define('PLUGIN_DUPLICATEGUARD_VERSION', '1.0.1');

require_once __DIR__ . '/src/TicketGuard.php';

function plugin_init_duplicateguard(): void
{
    global $PLUGIN_HOOKS;
    $PLUGIN_HOOKS['csrf_compliant']['duplicateguard'] = true;

    $PLUGIN_HOOKS[Hooks::PRE_ITEM_ADD]['duplicateguard'] = [
        Ticket::class => [PluginDuplicateguardTicketGuard::class, 'preItemAdd'],
    ];
    $PLUGIN_HOOKS[Hooks::PRE_ITEM_UPDATE]['duplicateguard'] = [
        Ticket::class => [PluginDuplicateguardTicketGuard::class, 'preItemUpdate'],
    ];
}

function plugin_version_duplicateguard(): array
{
    return [
        'name' => 'DuplicateGuard',
        'version' => PLUGIN_DUPLICATEGUARD_VERSION,
        'author' => 'Songa - Infraestructura IT',
        'license' => 'GPL-3.0-or-later',
        'homepage' => 'https://github.com/fmosquera01/duplicateguard',
        'requirements' => [
            'glpi' => ['min' => '11.0.0', 'max' => '11.99.99'],
            'php' => ['min' => '8.2.0'],
        ],
    ];
}

function plugin_duplicateguard_check_prerequisites(): bool
{
    return defined('GLPI_VERSION')
        && version_compare(GLPI_VERSION, '11.0.0', '>=')
        && version_compare(GLPI_VERSION, '12.0.0', '<');
}

function plugin_duplicateguard_check_config(): bool
{
    return true;
}
