<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Utils;

class Parser
{
    /**
     * @phpstan-param array<array-key, mixed> $notes
     * @phpstan-return array<array-key, mixed>
     */
    public static function parseNotes(array $notes): array
    {
        if (isset($notes['text'])) {
            $notes = [$notes];
        }

        return $notes;
    }

    /**
     * @phpstan-param array<array-key, mixed> $messages
     * @phpstan-return array<array-key, mixed>
     */
    public static function parseMessages(array $messages): array
    {
        $out = [];
        $messagelist = $messages['Message'];
        if (isset($messagelist['Header'])) {
            $messagelist = [$messagelist];
        }

        foreach ($messagelist as $message) {
            $out[] = [
                'header' => trim($message['Header']['$']),
                'text' => trim($message['Text']['$']),
            ];
        }

        return $out;
    }
}
