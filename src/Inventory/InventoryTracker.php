<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Inventory;

/**
 * Real-time inventory tracking
 */
final class InventoryTracker
{
    /** @var array<string, array<string, mixed>> */
    private array $movements = [];

    public function trackMovement(string $productId, string $type, int $quantity, string $location): void
    {
        $this->movements[] = [
            'product_id' => $productId,
            'type' => $type, // 'in', 'out', 'transfer'
            'quantity' => $quantity,
            'location' => $location,
            'timestamp' => time(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getMovements(string $productId): array
    {
        return array_filter(
            $this->movements,
            fn($m) => $m['product_id'] === $productId
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllMovements(): array
    {
        return $this->movements;
    }

    public function getCurrentStock(string $productId): int
    {
        $stock = 0;
        
        foreach ($this->movements as $movement) {
            if ($movement['product_id'] === $productId) {
                if ($movement['type'] === 'in') {
                    $stock += $movement['quantity'];
                } elseif ($movement['type'] === 'out') {
                    $stock -= $movement['quantity'];
                }
            }
        }

        return $stock;
    }
}
