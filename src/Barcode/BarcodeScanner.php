<?php

declare(strict_types=1);

namespace LaravelWarehousePro\Barcode;

/**
 * Barcode and QR code scanning
 */
final class BarcodeScanner
{
    /** @var array<string, string> */
    private array $barcodeMap = [];

    public function registerBarcode(string $barcode, string $productId): void
    {
        $this->barcodeMap[$barcode] = $productId;
    }

    public function scan(string $barcode): ?string
    {
        return $this->barcodeMap[$barcode] ?? null;
    }

    public function generateBarcode(string $productId): string
    {
        // Simple barcode generation (in production, use proper library)
        $barcode = 'BAR' . str_pad($productId, 10, '0', STR_PAD_LEFT);
        $this->registerBarcode($barcode, $productId);
        return $barcode;
    }

    public function isValidBarcode(string $barcode): bool
    {
        return isset($this->barcodeMap[$barcode]);
    }

    /**
     * @return array<string, string>
     */
    public function getAllBarcodes(): array
    {
        return $this->barcodeMap;
    }
}
