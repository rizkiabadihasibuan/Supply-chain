<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Database\Seeder;

/**
 * ════════════════════════════════════════════════════════════
 * WorldPortIndexSeeder
 * Source: World Port Index (WPI) – National Geospatial-Intelligence Agency (NGA)
 * Public Dataset: https://msi.nga.mil/Publications/WPI
 * Fields: Pelabuhan, Lokasi (lat/lng), Negara, Ukuran, Tipe Pelabuhan
 * ════════════════════════════════════════════════════════════
 */
class WorldPortIndexSeeder extends Seeder
{
    public function run(): void
    {
        // World Port Index Dataset (WPI) – 120 Major World Ports
        // Source: https://msi.nga.mil/Publications/WPI
        $ports = [
            // ── ASIA ──────────────────────────────────────────────────────
            ['code' => 'CNSHA', 'name' => 'Port of Shanghai',            'country' => 'CN', 'lat' =>  30.62, 'lng' => 122.05, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNNGB', 'name' => 'Port of Ningbo-Zhoushan',     'country' => 'CN', 'lat' =>  29.88, 'lng' => 121.55, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNSZX', 'name' => 'Port of Shenzhen (Yantian)', 'country' => 'CN', 'lat' =>  22.56, 'lng' => 114.27, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNCAN', 'name' => 'Port of Guangzhou',           'country' => 'CN', 'lat' =>  22.92, 'lng' => 113.36, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNTJN', 'name' => 'Port of Tianjin',             'country' => 'CN', 'lat' =>  39.00, 'lng' => 117.71, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNQIN', 'name' => 'Port of Qingdao',             'country' => 'CN', 'lat' =>  36.08, 'lng' => 120.32, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNXMN', 'name' => 'Port of Xiamen',              'country' => 'CN', 'lat' =>  24.45, 'lng' => 118.08, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CNHKG', 'name' => 'Port of Hong Kong',           'country' => 'CN', 'lat' =>  22.30, 'lng' => 114.17, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SGSGP', 'name' => 'Port of Singapore',           'country' => 'SG', 'lat' =>   1.26, 'lng' => 103.80, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IDTPP', 'name' => 'Tanjung Priok – Jakarta',     'country' => 'ID', 'lat' =>  -6.10, 'lng' => 106.88, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IDMAK', 'name' => 'Makassar Port',               'country' => 'ID', 'lat' =>  -5.14, 'lng' => 119.41, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IDSUB', 'name' => 'Tanjung Perak – Surabaya',    'country' => 'ID', 'lat' =>  -7.20, 'lng' => 112.73, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IDBPN', 'name' => 'Balikpapan Port',             'country' => 'ID', 'lat' =>  -1.27, 'lng' => 116.83, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IDBTM', 'name' => 'Batam Island Port',           'country' => 'ID', 'lat' =>   1.08, 'lng' => 104.01, 'size' => 'Small',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'JPYOK', 'name' => 'Port of Yokohama',            'country' => 'JP', 'lat' =>  35.44, 'lng' => 139.64, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'JPOSA', 'name' => 'Port of Osaka',               'country' => 'JP', 'lat' =>  34.65, 'lng' => 135.43, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'JPNGO', 'name' => 'Port of Nagoya',              'country' => 'JP', 'lat' =>  35.05, 'lng' => 136.88, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'JPKOB', 'name' => 'Port of Kobe',                'country' => 'JP', 'lat' =>  34.67, 'lng' => 135.19, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'KRPUS', 'name' => 'Port of Busan',               'country' => 'KR', 'lat' =>  35.09, 'lng' => 129.05, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'KRICN', 'name' => 'Port of Incheon',             'country' => 'KR', 'lat' =>  37.45, 'lng' => 126.59, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MYPKG', 'name' => 'Port Klang (Kelang)',         'country' => 'MY', 'lat' =>   2.99, 'lng' => 101.39, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MYTPP', 'name' => 'Tanjung Pelepas Port',        'country' => 'MY', 'lat' =>   1.36, 'lng' => 103.55, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'THBKK', 'name' => 'Laem Chabang Port',          'country' => 'TH', 'lat' =>  13.08, 'lng' => 100.88, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'VNSGN', 'name' => 'Ho Chi Minh City Port',      'country' => 'VN', 'lat' =>  10.77, 'lng' => 106.70, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'VNHAN', 'name' => 'Hai Phong Port',              'country' => 'VN', 'lat' =>  20.87, 'lng' => 106.67, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'PHMNL', 'name' => 'Manila International Container Terminal', 'country' => 'PH', 'lat' => 14.60, 'lng' => 120.97, 'size' => 'Medium', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['code' => 'PKPQG', 'name' => 'Port Qasim – Karachi',       'country' => 'PK', 'lat' =>  24.79, 'lng' =>  67.33, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'INMAA', 'name' => 'Chennai Port',                'country' => 'IN', 'lat' =>  13.10, 'lng' =>  80.29, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'INBOM', 'name' => 'Mumbai – JNPT Port',         'country' => 'IN', 'lat' =>  18.95, 'lng' =>  72.84, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'INPAV', 'name' => 'Mundra Port',                 'country' => 'IN', 'lat' =>  22.83, 'lng' =>  69.72, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'BDBZL', 'name' => 'Chittagong Port',             'country' => 'BD', 'lat' =>  22.34, 'lng' =>  91.82, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'LKCLM', 'name' => 'Colombo Port',               'country' => 'LK', 'lat' =>   6.94, 'lng' =>  79.84, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── MIDDLE EAST ────────────────────────────────────────────────
            ['code' => 'AEJEA', 'name' => 'Jebel Ali Port – Dubai',     'country' => 'AE', 'lat' =>  24.98, 'lng' =>  55.06, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'AEDXB', 'name' => 'Port of Dubai (Rashid)',     'country' => 'AE', 'lat' =>  25.26, 'lng' =>  55.30, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'OMMSQ', 'name' => 'Port Sultan Qaboos – Muscat','country' => 'OM', 'lat' =>  23.62, 'lng' =>  58.57, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SADMM', 'name' => 'Dammam – King Abdulaziz Port','country' => 'SA', 'lat' => 26.47, 'lng' =>  50.00, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SAJED', 'name' => 'Jeddah Islamic Port',        'country' => 'SA', 'lat' =>  21.46, 'lng' =>  39.17, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'IQBSR', 'name' => 'Basrah Port',                 'country' => 'IQ', 'lat' =>  30.39, 'lng' =>  47.98, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'KWKWI', 'name' => 'Shuwaikh Port – Kuwait',     'country' => 'KW', 'lat' =>  29.36, 'lng' =>  47.95, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ILHFA', 'name' => 'Port of Haifa',               'country' => 'IL', 'lat' =>  32.82, 'lng' =>  35.00, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── EUROPE ────────────────────────────────────────────────────
            ['code' => 'NLRTM', 'name' => 'Port of Rotterdam',           'country' => 'NL', 'lat' =>  51.90, 'lng' =>   4.13, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'DEHAM', 'name' => 'Port of Hamburg',             'country' => 'DE', 'lat' =>  53.55, 'lng' =>   9.95, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'BEANR', 'name' => 'Port of Antwerp',             'country' => 'BE', 'lat' =>  51.23, 'lng' =>   4.40, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'GBFXT', 'name' => 'Port of Felixstowe',         'country' => 'GB', 'lat' =>  51.96, 'lng' =>   1.35, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'GBSOU', 'name' => 'Southampton Port',            'country' => 'GB', 'lat' =>  50.89, 'lng' =>  -1.40, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'FRMRS', 'name' => 'Port of Marseille',           'country' => 'FR', 'lat' =>  43.30, 'lng' =>   5.33, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'FRLEH', 'name' => 'Port of Le Havre',            'country' => 'FR', 'lat' =>  49.49, 'lng' =>   0.11, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ESVLC', 'name' => 'Port of Valencia',            'country' => 'ES', 'lat' =>  39.43, 'lng' =>  -0.31, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ESALG', 'name' => 'Port of Algeciras',           'country' => 'ES', 'lat' =>  36.12, 'lng' =>  -5.43, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ITGOA', 'name' => 'Port of Genoa',               'country' => 'IT', 'lat' =>  44.40, 'lng' =>   8.93, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'GRPIR', 'name' => 'Port of Piraeus',             'country' => 'GR', 'lat' =>  37.95, 'lng' =>  23.62, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'TRIST', 'name' => 'Port of Istanbul (Haydarpasa)','country' => 'TR', 'lat' => 40.99, 'lng' =>  29.02, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'TRMER', 'name' => 'Port of Mersin',              'country' => 'TR', 'lat' =>  36.78, 'lng' =>  34.62, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SEARO', 'name' => 'Port of Gothenburg',          'country' => 'SE', 'lat' =>  57.68, 'lng' =>  11.97, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'NOSVG', 'name' => 'Port of Stavanger',           'country' => 'NO', 'lat' =>  58.97, 'lng' =>   5.73, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Fjord'],
            ['code' => 'PLGDX', 'name' => 'Port of Gdańsk',             'country' => 'PL', 'lat' =>  54.40, 'lng' =>  18.67, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'RULED', 'name' => 'St Petersburg Sea Port',      'country' => 'RU', 'lat' =>  59.93, 'lng' =>  30.22, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── NORTH AMERICA ──────────────────────────────────────────────
            ['code' => 'USLAX', 'name' => 'Port of Los Angeles',         'country' => 'US', 'lat' =>  33.72, 'lng' => -118.26, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USLGB', 'name' => 'Port of Long Beach',          'country' => 'US', 'lat' =>  33.76, 'lng' => -118.21, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USNYC', 'name' => 'Port of New York & New Jersey','country' => 'US', 'lat' =>  40.67, 'lng' => -74.05, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USSAV', 'name' => 'Port of Savannah',            'country' => 'US', 'lat' =>  31.97, 'lng' =>  -81.09, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'USHOU', 'name' => 'Port of Houston',             'country' => 'US', 'lat' =>  29.73, 'lng' =>  -95.27, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Inland'],
            ['code' => 'USORF', 'name' => 'Port of Norfolk (Hampton Roads)','country' => 'US', 'lat' => 36.84, 'lng' => -76.30, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USBAL', 'name' => 'Port of Baltimore',           'country' => 'US', 'lat' =>  39.26, 'lng' =>  -76.55, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USCHI', 'name' => 'Port of Charleston',          'country' => 'US', 'lat' =>  32.77, 'lng' =>  -79.96, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'USSET', 'name' => 'Port of Seattle',             'country' => 'US', 'lat' =>  47.60, 'lng' => -122.33, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CAVAN', 'name' => 'Port of Vancouver',           'country' => 'CA', 'lat' =>  49.29, 'lng' => -123.10, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'CAMTR', 'name' => 'Port of Montreal',            'country' => 'CA', 'lat' =>  45.51, 'lng' =>  -73.55, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'MXVER', 'name' => 'Port of Veracruz',            'country' => 'MX', 'lat' =>  19.20, 'lng' =>  -96.13, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MXMZT', 'name' => 'Port of Manzanillo (MX)',    'country' => 'MX', 'lat' =>  19.04, 'lng' => -104.32, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── SOUTH AMERICA ──────────────────────────────────────────────
            ['code' => 'BRSSS', 'name' => 'Port of Santos',              'country' => 'BR', 'lat' => -23.95, 'lng' =>  -46.33, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'BRRIO', 'name' => 'Port of Rio de Janeiro',      'country' => 'BR', 'lat' => -22.90, 'lng' =>  -43.17, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Bay'],
            ['code' => 'CLVAL', 'name' => 'Port of Valparaíso',         'country' => 'CL', 'lat' => -33.03, 'lng' =>  -71.63, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'PECLP', 'name' => 'Port of Callao',              'country' => 'PE', 'lat' => -12.05, 'lng' =>  -77.15, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'COBUN', 'name' => 'Port of Buenaventura',        'country' => 'CO', 'lat' =>   3.88, 'lng' =>  -77.05, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ARBA2', 'name' => 'Port of Buenos Aires',        'country' => 'AR', 'lat' => -34.61, 'lng' =>  -58.36, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'ECGYE', 'name' => 'Port of Guayaquil',           'country' => 'EC', 'lat' =>  -2.19, 'lng' =>  -79.89, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'UYMVD', 'name' => 'Port of Montevideo',         'country' => 'UY', 'lat' => -34.91, 'lng' =>  -56.21, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── AFRICA ────────────────────────────────────────────────────
            ['code' => 'ZADUR', 'name' => 'Port of Durban',              'country' => 'ZA', 'lat' => -29.87, 'lng' =>  31.02, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ZACPT', 'name' => 'Port of Cape Town',           'country' => 'ZA', 'lat' => -33.91, 'lng' =>  18.42, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'NGLAG', 'name' => 'Port of Lagos (Apapa)',       'country' => 'NG', 'lat' =>   6.45, 'lng' =>   3.37, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MOMBASA','name' => 'Port of Mombasa',            'country' => 'KE', 'lat' =>  -4.06, 'lng' =>  39.66, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'EGABD', 'name' => 'Alexandria Port',             'country' => 'EG', 'lat' =>  31.20, 'lng' =>  29.88, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'EGPSD', 'name' => 'Port Said',                   'country' => 'EG', 'lat' =>  31.26, 'lng' =>  32.31, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MAAGD', 'name' => 'Tanger Med Port',             'country' => 'MA', 'lat' =>  35.89, 'lng' =>  -5.49, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'TNDJI', 'name' => 'Port of Djibouti',            'country' => 'DJ', 'lat' =>  11.60, 'lng' =>  43.14, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'GHTEM', 'name' => 'Tema Port (Ghana)',           'country' => 'GH', 'lat' =>   5.63, 'lng' =>   0.01, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SNDKR', 'name' => 'Port of Dakar',               'country' => 'SN', 'lat' =>  14.69, 'lng' => -17.43, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'TZDAR', 'name' => 'Port of Dar es Salaam',       'country' => 'TZ', 'lat' =>  -6.82, 'lng' =>  39.29, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'ETDJI', 'name' => 'Port of Berbera',             'country' => 'ET', 'lat' =>  10.44, 'lng' =>  45.01, 'size' => 'Small',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── OCEANIA ───────────────────────────────────────────────────
            ['code' => 'AUSYD', 'name' => 'Port of Sydney (Botany)',     'country' => 'AU', 'lat' => -33.97, 'lng' => 151.19, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'AUMEL', 'name' => 'Port of Melbourne',           'country' => 'AU', 'lat' => -37.83, 'lng' => 144.89, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'AUBNE', 'name' => 'Port of Brisbane',            'country' => 'AU', 'lat' => -27.38, 'lng' => 153.17, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'AUFRM', 'name' => 'Port of Fremantle',          'country' => 'AU', 'lat' => -32.05, 'lng' => 115.74, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'NZAKL', 'name' => 'Port of Auckland',           'country' => 'NZ', 'lat' => -36.84, 'lng' => 174.77, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'NZLYT', 'name' => 'Port of Lyttelton',          'country' => 'NZ', 'lat' => -43.60, 'lng' => 172.72, 'size' => 'Small',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'PGPOM', 'name' => 'Port of Port Moresby',       'country' => 'PG', 'lat' =>  -9.44, 'lng' => 147.16, 'size' => 'Small',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],

            // ── CARIBBEAN & CENTRAL AMERICA ────────────────────────────────
            ['code' => 'PAMIT', 'name' => 'Manzanillo International Terminal (Panama)', 'country' => 'PA', 'lat' =>  9.38, 'lng' => -79.91, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['code' => 'CUBHA', 'name' => 'Port of Havana',              'country' => 'CU', 'lat' =>  23.13, 'lng' => -82.37, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Bay'],
            ['code' => 'JMKIN', 'name' => 'Kingston Container Terminal', 'country' => 'JM', 'lat' =>  17.97, 'lng' => -76.79, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'DOPPP', 'name' => 'Port of Rio Haina',           'country' => 'DO', 'lat' =>  18.43, 'lng' => -70.00, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],

            // ── SPECIAL STRATEGIC ──────────────────────────────────────────
            ['code' => 'DJJIB', 'name' => 'Port of Djibouti (Doraleh)', 'country' => 'DJ', 'lat' =>  11.52, 'lng' =>  43.13, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'SCLNG', 'name' => 'Colombo North Port',         'country' => 'LK', 'lat' =>   6.96, 'lng' =>  79.85, 'size' => 'Large',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
            ['code' => 'MMRGN', 'name' => 'Yangon Port',                 'country' => 'MM', 'lat' =>  16.77, 'lng' =>  96.16, 'size' => 'Medium', 'type' => 'Seaport',    'harbor' => 'River'],
            ['code' => 'KHDAD', 'name' => 'Sihanoukville Port',         'country' => 'KH', 'lat' =>  10.63, 'lng' => 103.53, 'size' => 'Small',  'type' => 'Seaport',    'harbor' => 'Coastal Natural'],
        ];

        $inserted = 0;
        $skipped  = 0;

        foreach ($ports as $p) {
            $country = Country::where('code', $p['country'])->first();
            if (! $country) {
                $skipped++;
                continue;
            }

            Port::updateOrCreate(
                ['code' => $p['code']],
                [
                    'name'        => $p['name'],
                    'country_id'  => $country->id,
                    'latitude'    => $p['lat'],
                    'longitude'   => $p['lng'],
                    'size'        => $p['size'],
                    'type'        => $p['type'],
                    'harbor_type' => $p['harbor'],
                    'facilities'  => [
                        'crane'       => in_array($p['size'], ['Large', 'Medium']),
                        'customs'     => true,
                        'storage'     => true,
                        'refueling'   => $p['size'] === 'Large',
                        'containers'  => $p['type'] === 'Seaport',
                    ],
                ]
            );
            $inserted++;
        }

        $this->command->info("WorldPortIndexSeeder: {$inserted} ports inserted/updated, {$skipped} skipped (country not found).");
    }
}
