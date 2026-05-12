<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case UTILITIES   = 'utilities';
    case SALARY      = 'salary';
    case MAINTENANCE = 'maintenance';
    case CLEANING    = 'cleaning';
    case LAUNDRY     = 'laundry';
    case MARKETING   = 'marketing';
    case TRANSPORT   = 'transport';
    case FOOD        = 'food';
    case TAXES       = 'taxes';
    case SUPPLIES    = 'supplies';
    case OTHER       = 'other';

    public function label(): string
    {
        return match ($this) {
            self::UTILITIES   => 'Charges (eau, électricité, gaz)',
            self::SALARY      => 'Salaires',
            self::MAINTENANCE => 'Maintenance',
            self::CLEANING    => 'Nettoyage',
            self::LAUNDRY     => 'Blanchisserie',
            self::MARKETING   => 'Marketing',
            self::TRANSPORT   => 'Transport',
            self::FOOD        => 'Nourriture',
            self::TAXES       => 'Taxes',
            self::SUPPLIES    => 'Fournitures',
            self::OTHER       => 'Autre',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::UTILITIES   => '⚡',
            self::SALARY      => '💼',
            self::MAINTENANCE => '🔧',
            self::CLEANING    => '🧽',
            self::LAUNDRY     => '🧺',
            self::MARKETING   => '📣',
            self::TRANSPORT   => '🚗',
            self::FOOD        => '🍽️',
            self::TAXES       => '🧾',
            self::SUPPLIES    => '📦',
            self::OTHER       => '📌',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->emoji() . ' ' . $case->label();
        }
        return $out;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}