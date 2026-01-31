<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Events;

/**
 * Event sourcing events for inventory
 */
final class InventoryEvents
{
    /** @var array<int, array<string, mixed>> */
    private array $events = [];

    public function recordEvent(string $type, array $data): void
    {
        $this->events[] = [
            'type' => $type,
            'data' => $data,
            'timestamp' => microtime(true),
            'id' => count($this->events) + 1,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllEvents(): array
    {
        return $this->events;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEventsByType(string $type): array
    {
        return array_filter(
            $this->events,
            fn($event) => $event['type'] === $type
        );
    }

    public function replayEvents(): array
    {
        $state = [];
        
        foreach ($this->events as $event) {
            // Rebuild state from events
            $state[] = $event;
        }

        return $state;
    }
}
