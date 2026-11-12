<?php
declare(strict_types=1);

/**
 * DuplicateGuard ticket validation.
 *
 * Blocks a ticket when another non-deleted ticket has the same title and is
 * not closed. The validation is server-side and applies to all ticket origins.
 */
class PluginDuplicateguardTicketGuard
{
    public static function preItemAdd(CommonDBTM $item): void
    {
        if (!$item instanceof Ticket) {
            return;
        }

        $input = is_array($item->input) ? $item->input : [];
        $title = self::normalizeTitle($input['name'] ?? '');

        if ($title === '') {
            return;
        }

        $duplicate = self::findOpenDuplicate($title);

        if ($duplicate !== null) {
            self::reject($item, $duplicate);
        }
    }

    public static function preItemUpdate(CommonDBTM $item): void
    {
        if (!$item instanceof Ticket) {
            return;
        }

        $input = is_array($item->input) ? $item->input : [];

        if (!array_key_exists('name', $input)) {
            return;
        }

        $title = self::normalizeTitle($input['name']);

        if ($title === '') {
            return;
        }

        $currentId = (int) ($item->fields['id'] ?? $input['id'] ?? 0);
        $duplicate = self::findOpenDuplicate($title, $currentId);

        if ($duplicate !== null) {
            self::reject($item, $duplicate);
        }
    }

    private static function normalizeTitle(mixed $title): string
    {
        if (!is_scalar($title)) {
            return '';
        }

        return trim((string) $title);
    }

    /**
     * @return array{id:int, name:string, status:int}|null
     */
    private static function findOpenDuplicate(string $title, int $excludeId = 0): ?array
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => ['id', 'name', 'status'],
            'FROM' => Ticket::getTable(),
            'WHERE' => [
                'name' => $title,
                'is_deleted' => 0,
            ],
            'ORDER' => ['id ASC'],
            'LIMIT' => 100,
        ]);

        foreach ($iterator as $row) {
            $ticketId = (int) ($row['id'] ?? 0);

            if ($ticketId === $excludeId) {
                continue;
            }

            if ((int) ($row['status'] ?? 0) !== CommonITILObject::CLOSED) {
                return [
                    'id' => $ticketId,
                    'name' => (string) ($row['name'] ?? ''),
                    'status' => (int) ($row['status'] ?? 0),
                ];
            }
        }

        return null;
    }

    /**
     * @param array{id:int, name:string, status:int} $duplicate
     */
    private static function reject(CommonDBTM $item, array $duplicate): void
    {
        $message = sprintf(
            'No se puede guardar este ticket porque ya existe el ticket #%d con el mismo título: "%s". Debe cerrarse el ticket existente antes de crear otro.',
            $duplicate['id'],
            htmlescape($duplicate['name'])
        );

        Session::addMessageAfterRedirect($message, false, ERROR);
        $item->input = false;
    }
}
