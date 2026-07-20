<?php
namespace App\Enums;
/** CacheKey – Kunci cache yang digunakan secara konsisten */
enum CacheKey: string {
    case Countries   = 'countries_all';
    case Weather     = 'weather_{code}';
    case Currency    = 'currency_{base}';
    case RiskScore   = 'risk_{code}';
    case News        = 'news_latest';
    case Ports       = 'ports_all';
}
