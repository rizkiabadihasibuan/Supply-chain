<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCapitalsSeeder extends Seeder
{
    public function run(): void
    {
        $capitals = [
            'IN'=>'New Delhi','KR'=>'Seoul','PK'=>'Islamabad','BD'=>'Dhaka','VN'=>'Hanoi',
            'TH'=>'Bangkok','MY'=>'Kuala Lumpur','PH'=>'Manila','MM'=>'Naypyidaw',
            'KH'=>'Phnom Penh','AF'=>'Kabul','LK'=>'Colombo','IR'=>'Tehran','IQ'=>'Baghdad',
            'HK'=>'Hong Kong','TW'=>'Taipei','MN'=>'Ulaanbaatar','KZ'=>'Astana',
            'UZ'=>'Tashkent','NP'=>'Kathmandu','SA'=>'Riyadh','AE'=>'Abu Dhabi',
            'QA'=>'Doha','KW'=>'Kuwait City','IL'=>'Jerusalem','JO'=>'Amman',
            'LB'=>'Beirut','OM'=>'Muscat','DE'=>'Berlin','FR'=>'Paris','GB'=>'London',
            'IT'=>'Rome','ES'=>'Madrid','PL'=>'Warsaw','PT'=>'Lisbon','BE'=>'Brussels',
            'AT'=>'Vienna','SE'=>'Stockholm','NO'=>'Oslo','FI'=>'Helsinki',
            'RO'=>'Bucharest','CZ'=>'Prague','HU'=>'Budapest','GR'=>'Athens',
            'RU'=>'Moscow','TR'=>'Ankara','BY'=>'Minsk','RS'=>'Belgrade','HR'=>'Zagreb',
            'BG'=>'Sofia','SK'=>'Bratislava','IE'=>'Dublin','CA'=>'Ottawa',
            'AR'=>'Buenos Aires','CO'=>'Bogota','CL'=>'Santiago','PE'=>'Lima',
            'VE'=>'Caracas','EC'=>'Quito','BO'=>'Sucre','PY'=>'Asuncion',
            'UY'=>'Montevideo','GT'=>'Guatemala City','CU'=>'Havana','DO'=>'Santo Domingo',
            'PA'=>'Panama City','CR'=>'San Jose','HN'=>'Tegucigalpa','JM'=>'Kingston',
            'ET'=>'Addis Ababa','EG'=>'Cairo','CD'=>'Kinshasa','TZ'=>'Dodoma',
            'KE'=>'Nairobi','DZ'=>'Algiers','UG'=>'Kampala','GH'=>'Accra','MA'=>'Rabat',
            'NG'=>'Abuja','AO'=>'Luanda','CM'=>'Yaounde','CI'=>'Yamoussoukro',
            'TN'=>'Tunis','LY'=>'Tripoli','ZW'=>'Harare','ZM'=>'Lusaka',
            'SN'=>'Dakar','MZ'=>'Maputo','AU'=>'Canberra','NZ'=>'Wellington',
            'PG'=>'Port Moresby','FJ'=>'Suva','SD'=>'Khartoum','YE'=>'Sanaa',
            'SY'=>'Damascus','UA'=>'Kyiv','SO'=>'Mogadishu','SG'=>'Singapore',
            'CH'=>'Bern','DK'=>'Copenhagen','ID'=>'Jakarta','JP'=>'Tokyo',
            'CN'=>'Beijing','NL'=>'Amsterdam','US'=>'Washington DC','BR'=>'Brasilia',
            'ZA'=>'Pretoria',
        ];
        foreach ($capitals as $code => $capital) {
            DB::table('countries')->where('code', $code)->update(['capital' => $capital]);
        }
        $this->command->info('Capitals updated! With capital: ' . DB::table('countries')->whereNotNull('capital')->count());
    }
}