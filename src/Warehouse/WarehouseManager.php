<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Warehouse;

/**
 * Multi-warehouse management
 */
final class WarehouseManager
{
    /** @var array<string, array<string, mixed>> */
    private array $warehouses = [];

    /** @var array<string, array<string, mixed>> */
    private array $inventory = [];

    public function addWarehouse(string $id, string $name, string $location): void
    {
        $this->warehouses[$id] = [
            'id' => $id,
            'name' => $name,
            'location' => $location,
            'created_at' => time(),
        ];
    }

    public function addInventory(string $warehouseId, string $productId, int $quantity): void
    {
        $key = "$warehouseId:$productId";
        
        if (isset($this->inventory[$key])) {
            $this->inventory[$key]['quantity'] += $quantity;
        } else {
            $this->inventory[$key] = [
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }
    }

    public function getInventory(string $warehouseId, string $productId): int
    {
        $key = "$warehouseId:$productId";
        return $this->inventory[$key]['quantity'] ?? 0;
    }

    public function transferInventory(
        string $fromWarehouseId,
        string $toWarehouseId,
        string $productId,
        int $quantity
    ): void {
        $fromKey = "$fromWarehouseId:$productId";
        $toKey = "$toWarehouseId:$productId";

        if (!isset($this->inventory[$fromKey]) || $this->inventory[$fromKey]['quantity'] < $quantity) {
            throw new \RuntimeException('Insufficient inventory');
        }

        $this->inventory[$fromKey]['quantity'] -= $quantity;

        if (isset($this->inventory[$toKey])) {
            $this->inventory[$toKey]['quantity'] += $quantity;
        } else {
            $this->inventory[$toKey] = [
                'warehouse_id' => $toWarehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getAllWarehouses(): array
    {
        return $this->warehouses;
    }
}
