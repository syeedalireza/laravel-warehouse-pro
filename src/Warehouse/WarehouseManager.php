<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Warehouse;

final class WarehouseManager
{
    private array $warehouses = [];

    public function addWarehouse(string $id, string $name): void
    {
        $this->warehouses[$id] = ['name' => $name, 'inventory' => []];
    }

    public function trackInventory(string $warehouseId, string $productId, int $quantity): void
    {
        $this->warehouses[$warehouseId]['inventory'][$productId] = $quantity;
    }

    public function getInventory(string $warehouseId): array
    {
        return $this->warehouses[$warehouseId]['inventory'] ?? [];
    }
}
