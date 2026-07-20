<?php
namespace App\Helpers;
/**
 * FormatHelper – Helper untuk format angka, tanggal, dan teks
 */
class FormatHelper {
    public static function number(float|int $n, int $dec = 0): string {
        return number_format($n, $dec, ',', '.');
    }
    public static function currency(float $amount, string $code = 'USD'): string {
        return $code . ' ' . self::number($amount, 2);
    }
    public static function date(string $date, string $format = 'd M Y'): string {
        return date($format, strtotime($date));
    }
    public static function truncate(string $text, int $max = 100): string {
        return strlen($text) > $max ? substr($text, 0, $max) . '...' : $text;
    }
}
