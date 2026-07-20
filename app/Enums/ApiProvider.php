<?php
namespace App\Enums;
/** ApiProvider – Daftar external API provider yang digunakan */
enum ApiProvider: string {
    case RestCountries  = 'rest-countries';
    case WorldBank      = 'world-bank';
    case OpenMeteo      = 'open-meteo';
    case ExchangeRate   = 'exchange-rate';
    case WorldPortIndex = 'world-port-index';
    case GNews          = 'gnews';
    case OpenStreetMap  = 'openstreetmap';
}
