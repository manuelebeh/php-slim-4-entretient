<?php

namespace App\Helpers;

class Utility
{
    /**
     * Formatez une valeur en dollars.
     *
     * @param float $amount
     * @return string
     */
    public static function formatDollars(float $amount): string
    {
        return '$' . number_format($amount, 2);
    }

    /**
     * Extraire les cinq premiers chiffres du code postal
     *
     * @param string $postalCode
     * @return string
     */
    public static function formatPostalCode(string $postalCode): string
    {
        return substr($postalCode, 0, 5);
    }

    /**
     * Calculer le prix total en fonction du nombre d'adresses.
     *
     * @param int $numberOfAddresses
     * @return string
     */
    public static function calculateTotalPrice(int $numberOfAddresses): string
    {
        $pricePerAddress = 20;
        $totalPrice = $numberOfAddresses * $pricePerAddress;
        return self::formatDollars($totalPrice);
    }
}