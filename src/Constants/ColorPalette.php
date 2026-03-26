<?php

namespace App\Constants;

/**
 * Color constants for the application
 * Standardized color palette used throughout OdontoManage
 */
class ColorPalette
{
    // Primary Colors - Odontogram Standards
    public const RED = '#FF4136';           // Patología o lesión pendiente de hacer
    public const BLUE = '#0074D9';          // Tratamiento ya realizado
    public const GREEN = '#2ECC40';         // Caries radiográficas
    public const YELLOW = '#FFDC00';        // Sellado de fosas y fisuras
    public const BLACK = '#111111';         // Ausencias naturales

    // Status constants
    public const STATUS_PENDING = 'pending';      // Pendiente
    public const STATUS_COMPLETED = 'completed';  // Finalizado

    /**
     * Odontogram pathology meanings
     */
    public const ODONTOGRAM_MEANINGS = [
        self::RED => 'Patología o lesión - Pendiente de hacer',
        self::BLUE => 'Tratamiento ya realizado',
        self::GREEN => 'Caries radiográficas',
        self::YELLOW => 'Sellado de fosas y fisuras',
        self::BLACK => 'Ausencias naturales'
    ];

    /**
     * Get color by status
     * 
     * @param string $status The status (pending or completed)
     * @return string The color code
     */
    public static function getColorByStatus(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => self::RED,
            self::STATUS_COMPLETED => self::BLUE,
            default => self::RED,
        };
    }

    /**
     * Get status meaning
     * 
     * @param string $color The color code
     * @return string The meaning
     */
    public static function getMeaning(string $color): string
    {
        return self::ODONTOGRAM_MEANINGS[$color] ?? 'Desconocido';
    }

    /**
     * Get all available colors
     * 
     * @return array<string, string> Colors with their meanings
     */
    public static function getAll(): array
    {
        return self::ODONTOGRAM_MEANINGS;
    }
}
