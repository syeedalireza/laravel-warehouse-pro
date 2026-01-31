<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Projections;

/**
 * Read model projections for inventory
 */
final class InventoryProjection
{
    /** @var array<string, mixed> */
    private array $projection = [];

    public function project(array $events): void
    {
        foreach ($events as $event) {
            $this->applyEvent($event);
        }
    }

    private function applyEvent(array $event): void
    {
        $type = $event['type'] ?? '';
        $data = $event['data'] ?? [];

        match ($type) {
            'inventory_added' => $this->handleInventoryAdded($data),
            'inventory_removed' => $this->handleInventoryRemoved($data),
            default => null,
        };
    }

    private function handleInventoryAdded(array $data): void
    {
        $productId = $data['product_id'] ?? '';
        $quantity = $data['quantity'] ?? 0;

        if (!isset($this->projection[$productId])) {
            $this->projection[$productId] = 0;
        }

        $this->projection[$productId] += $quantity;
    }

    private function handleInventoryRemoved(array $data): void
    {
        $productId = $data['product_id'] ?? '';
        $quantity = $data['quantity'] ?? 0;

        if (isset($this->projection[$productId])) {
            $this->projection[$productId] -= $quantity;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getProjection(): array
    {
        return $this->projection;
    }
}
