<?php
namespace App\Helpers;
/**
 * CacheKeyHelper – Helper untuk generate cache key yang konsisten
 */
class CacheKeyHelper {
    public static function country(string $code): string  { return 'country_' . strtolower($code); }
    public static function weather(string $code): string  { return 'weather_' . strtolower($code); }
    public static function currency(string $base): string { return 'currency_' . strtoupper($base); }
    public static function risk(string $code): string     { return 'risk_score_' . strtolower($code); }
    public static function news(string $topic = 'all'): string { return 'news_' . $topic; }
}
