<?php

namespace App\Traits;

use App\Models\Car;

trait ConvertsUnits
{
    /**
     * Конвертирует расстояние (км → мили или наоборот)
     */
    public function convertDistance($km, Car $car)
    {
        if ($car->distance_unit === 'miles') {
            return round($km * 0.621371, 1);
        }
        return $km;
    }
    
    /**
     * Конвертирует объем (литры → галлоны или наоборот)
     */
    public function convertVolume($liters, Car $car)
    {
        if ($car->volume_unit === 'gallons') {
            return round($liters * 0.264172, 2);
        }
        return $liters;
    }
    
    /**
     * Конвертирует расход топлива (л/100км → миль на галлон)
     */
    public function convertFuelConsumption($litersPer100km, Car $car)
    {
        if ($litersPer100km <= 0) {
            return 0;
        }
        
        if ($car->distance_unit === 'miles' && $car->volume_unit === 'gallons') {
            return round(235.214583 / $litersPer100km, 1);
        }
        return $litersPer100km;
    }
    
    /**
     * Конвертирует валюту
     */
    public function convertCurrency($amount, Car $car)
    {
        if ($amount <= 0) {
            return 0;
        }
        
        switch ($car->currency) {
            case 'USD':
                return round($amount / 95, 2);
            case 'EUR':
                return round($amount / 100, 2);
            default:
                return $amount;
        }
    }
    
    /**
     * Получает символ валюты
     */
    public function getCurrencySymbol(Car $car)
    {
        switch ($car->currency) {
            case 'USD':
                return '$';
            case 'EUR':
                return '€';
            default:
                return '₽';
        }
    }
    
    /**
     * Получает единицу расстояния
     */
    public function getDistanceUnit(Car $car)
    {
        return $car->distance_unit === 'miles' ? 'mi' : 'км';
    }
    
    /**
     * Получает единицу объема
     */
    public function getVolumeUnit(Car $car)
    {
        return $car->volume_unit === 'gallons' ? 'gal' : 'л';
    }
}