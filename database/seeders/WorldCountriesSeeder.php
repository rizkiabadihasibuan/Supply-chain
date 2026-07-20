<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use App\Models\RiskClassification;
use App\Models\RiskScore;
use Illuminate\Database\Seeder;

class WorldCountriesSeeder extends Seeder
{
    public function run(): void
    {
        $regionNames = ['Global', 'Asia', 'Europe', 'Americas', 'Africa', 'Oceania'];
        $regionMap   = [];
        foreach ($regionNames as $r) {
            $regionMap[strtolower($r)] = Region::firstOrCreate(['name' => $r]);
        }

        $currencyList = [
            ['USD','US Dollar','$'],['EUR','Euro','Ã¢â€šÂ¬'],['GBP','British Pound','Ã‚Â£'],
            ['JPY','Japanese Yen','Ã‚Â¥'],['CNY','Chinese Yuan','Ã‚Â¥'],['INR','Indian Rupee','Ã¢â€šÂ¹'],
            ['AUD','Australian Dollar','A$'],['CAD','Canadian Dollar','C$'],
            ['KRW','South Korean Won','Ã¢â€šÂ©'],['SAR','Saudi Riyal','SR'],['TRY','Turkish Lira','TL'],
            ['IDR','Indonesian Rupiah','Rp'],['SGD','Singapore Dollar','S$'],
            ['MYR','Malaysian Ringgit','RM'],['PHP','Philippine Peso','PHP'],
            ['THB','Thai Baht','THB'],['VND','Vietnamese Dong','VND'],
            ['BRL','Brazilian Real','R$'],['ARS','Argentine Peso','$'],['CLP','Chilean Peso','$'],
            ['COP','Colombian Peso','$'],['PEN','Peruvian Sol','S/'],['ZAR','South African Rand','R'],
            ['NGN','Nigerian Naira','NGN'],['EGP','Egyptian Pound','EGP'],
            ['KES','Kenyan Shilling','KSh'],['MAD','Moroccan Dirham','MAD'],
            ['NOK','Norwegian Krone','kr'],['SEK','Swedish Krona','kr'],['DKK','Danish Krone','kr'],
            ['PLN','Polish Zloty','zl'],['CZK','Czech Koruna','Kc'],['HUF','Hungarian Forint','Ft'],
            ['RON','Romanian Leu','lei'],['UAH','Ukrainian Hryvnia','UAH'],
            ['AED','UAE Dirham','AED'],['QAR','Qatari Riyal','QAR'],['KWD','Kuwaiti Dinar','KWD'],
            ['ILS','Israeli Shekel','ILS'],['PKR','Pakistani Rupee','PKR'],['BDT','Bangladeshi Taka','BDT'],
            ['LKR','Sri Lankan Rupee','LKR'],['MMK','Myanmar Kyat','K'],['KHR','Cambodian Riel','KHR'],
            ['NZD','New Zealand Dollar','NZ$'],['HKD','Hong Kong Dollar','HK$'],['TWD','New Taiwan Dollar','NT$'],
            ['IRR','Iranian Rial','IRR'],['IQD','Iraqi Dinar','IQD'],['AFN','Afghan Afghani','AFN'],
            ['ETB','Ethiopian Birr','ETB'],['GHS','Ghanaian Cedi','GHS'],['TZS','Tanzanian Shilling','TZS'],
            ['UGX','Ugandan Shilling','UGX'],['XOF','CFA Franc','CFA'],['XAF','CFA Franc','FCFA'],
            ['AOA','Angolan Kwanza','Kz'],['RUB','Russian Ruble','RUB'],
        ];
        $currencies = [];
        foreach ($currencyList as [$code, $name, $symbol]) {
            $currencies[$code] = Currency::firstOrCreate(['code' => $code], ['name' => $name, 'symbol' => $symbol]);
        }

        $data = [
            ['India','IN','asia','INR',20.59,78.96,42.0,'Medium',1428000000,'New Delhi'],
            ['South Korea','KR','asia','KRW',35.90,127.76,15.0,'Very Low',51700000,'Seoul'],
            ['Pakistan','PK','asia','PKR',30.37,69.34,72.0,'High',231000000,'Islamabad'],
            ['Bangladesh','BD','asia','BDT',23.68,90.35,55.0,'Medium',170000000,'Dhaka'],
            ['Vietnam','VN','asia','VND',14.05,108.27,32.0,'Low',97000000,'Hanoi'],
            ['Thailand','TH','asia','THB',15.87,100.99,28.0,'Low',71000000,'Bangkok'],
            ['Malaysia','MY','asia','MYR',4.21,101.97,22.0,'Low',33000000,'Kuala Lumpur'],
            ['Philippines','PH','asia','PHP',12.87,121.77,38.0,'Low',115000000,'Manila'],
            ['Myanmar','MM','asia','MMK',21.91,95.95,68.0,'High',54000000,'Naypyidaw'],
            ['Cambodia','KH','asia','KHR',12.56,104.99,45.0,'Medium',17000000,'Phnom Penh'],
            ['Afghanistan','AF','asia','AFN',33.93,67.70,91.0,'Critical',41000000,'Kabul'],
            ['Sri Lanka','LK','asia','LKR',7.87,80.77,58.0,'Medium',22000000,'Colombo'],
            ['Iran','IR','asia','IRR',32.42,53.68,78.0,'High',88000000,'Tehran'],
            ['Iraq','IQ','asia','IQD',33.22,43.68,74.0,'High',41000000,'Baghdad'],
            ['Hong Kong','HK','asia','HKD',22.39,114.10,12.0,'Very Low',7500000,'Hong Kong'],
            ['Taiwan','TW','asia','TWD',23.69,120.96,18.0,'Very Low',23500000,'Taipei'],
            ['Mongolia','MN','asia','USD',46.86,103.84,35.0,'Low',3400000,'Ulaanbaatar'],
            ['Kazakhstan','KZ','asia','USD',48.01,66.92,40.0,'Medium',19000000,'Astana'],
            ['Uzbekistan','UZ','asia','USD',41.37,64.58,44.0,'Medium',36000000,'Tashkent'],
            ['Nepal','NP','asia','USD',28.39,84.12,50.0,'Medium',30000000,'Kathmandu'],
            ['Saudi Arabia','SA','asia','SAR',23.88,45.08,30.0,'Low',36000000,'Riyadh'],
            ['UAE','AE','asia','AED',23.42,53.84,14.0,'Very Low',10000000,'Abu Dhabi'],
            ['Qatar','QA','asia','QAR',25.35,51.18,16.0,'Very Low',2900000,'Doha'],
            ['Kuwait','KW','asia','KWD',29.31,47.48,22.0,'Low',4300000,'Kuwait City'],
            ['Israel','IL','asia','ILS',31.04,34.85,45.0,'Medium',9500000,'Jerusalem'],
            ['Jordan','JO','asia','USD',30.58,36.23,38.0,'Low',10000000,'Amman'],
            ['Lebanon','LB','asia','USD',33.85,35.86,82.0,'Critical',5500000,'Beirut'],
            ['Oman','OM','asia','USD',21.47,55.97,20.0,'Low',4500000,'Muscat'],
            ['Germany','DE','europe','EUR',51.16,10.45,12.0,'Very Low',84000000,'Berlin'],
            ['France','FR','europe','EUR',46.22,2.21,14.0,'Very Low',68000000,'Paris'],
            ['United Kingdom','GB','europe','GBP',55.37,-3.43,13.0,'Very Low',68000000,'London'],
            ['Italy','IT','europe','EUR',41.87,12.56,18.0,'Very Low',60000000,'Rome'],
            ['Spain','ES','europe','EUR',40.46,-3.74,16.0,'Very Low',47000000,'Madrid'],
            ['Poland','PL','europe','PLN',51.91,19.14,22.0,'Low',38000000,'Warsaw'],
            ['Portugal','PT','europe','EUR',39.39,-8.22,15.0,'Very Low',10000000,'Lisbon'],
            ['Belgium','BE','europe','EUR',50.50,4.47,13.0,'Very Low',11000000,'Brussels'],
            ['Austria','AT','europe','EUR',47.51,14.55,11.0,'Very Low',9000000,'Vienna'],
            ['Sweden','SE','europe','SEK',60.12,18.64,10.0,'Very Low',10500000,'Stockholm'],
            ['Norway','NO','europe','NOK',60.47,8.46,10.0,'Very Low',5400000,'Oslo'],
            ['Finland','FI','europe','EUR',61.92,25.74,9.0,'Very Low',5500000,'Helsinki'],
            ['Romania','RO','europe','RON',45.94,24.96,30.0,'Low',19000000,'Bucharest'],
            ['Czech Republic','CZ','europe','CZK',49.81,15.47,18.0,'Very Low',10800000,'Prague'],
            ['Hungary','HU','europe','HUF',47.16,19.50,22.0,'Low',10000000,'Budapest'],
            ['Greece','GR','europe','EUR',39.07,21.82,25.0,'Low',10700000,'Athens'],
            ['Russia','RU','europe','RUB',61.52,105.31,80.0,'High',144000000,'Moscow'],
            ['Turkey','TR','europe','TRY',38.96,35.24,55.0,'Medium',85000000,'Ankara'],
            ['Belarus','BY','europe','USD',53.70,28.04,72.0,'High',9400000,'Minsk'],
            ['Serbia','RS','europe','USD',44.01,21.00,28.0,'Low',7000000,'Belgrade'],
            ['Croatia','HR','europe','EUR',45.10,15.20,18.0,'Very Low',3900000,'Zagreb'],
            ['Bulgaria','BG','europe','EUR',42.73,25.48,25.0,'Low',6500000,'Sofia'],
            ['Slovakia','SK','europe','EUR',48.66,19.69,16.0,'Very Low',5400000,'Bratislava'],
            ['Ireland','IE','europe','EUR',53.14,-8.24,11.0,'Very Low',5100000,'Dublin'],
            ['Canada','CA','americas','CAD',56.13,-106.35,11.0,'Very Low',38000000,'Ottawa'],
            ['Argentina','AR','americas','ARS',-38.41,-63.61,52.0,'Medium',46000000,'Buenos Aires'],
            ['Colombia','CO','americas','COP',4.57,-74.29,48.0,'Medium',51000000,'Bogota'],
            ['Chile','CL','americas','CLP',-35.67,-71.54,30.0,'Low',19000000,'Santiago'],
            ['Peru','PE','americas','PEN',-9.19,-75.01,42.0,'Medium',33000000,'Lima'],
            ['Venezuela','VE','americas','USD',6.42,-66.59,82.0,'Critical',28000000,'Caracas'],
            ['Ecuador','EC','americas','USD',-1.83,-78.18,45.0,'Medium',18000000,'Quito'],
            ['Bolivia','BO','americas','USD',-16.29,-63.58,50.0,'Medium',12000000,'Sucre'],
            ['Paraguay','PY','americas','USD',-23.44,-58.44,38.0,'Low',7500000,'Asuncion'],
            ['Uruguay','UY','americas','USD',-32.52,-55.76,22.0,'Low',3500000,'Montevideo'],
            ['Guatemala','GT','americas','USD',15.78,-90.23,52.0,'Medium',17000000,'Guatemala City'],
            ['Cuba','CU','americas','USD',21.52,-77.78,65.0,'High',11000000,'Havana'],
            ['Dominican Republic','DO','americas','USD',18.73,-70.16,35.0,'Low',11000000,'Santo Domingo'],
            ['Panama','PA','americas','USD',8.53,-80.78,28.0,'Low',4200000,'Panama City'],
            ['Costa Rica','CR','americas','USD',9.75,-83.75,20.0,'Low',5100000,'San Jose'],
            ['Honduras','HN','americas','USD',15.20,-86.24,55.0,'Medium',10000000,'Tegucigalpa'],
            ['Jamaica','JM','americas','USD',18.11,-77.29,40.0,'Medium',3000000,'Kingston'],
            ['Ethiopia','ET','africa','ETB',9.14,40.48,60.0,'High',126000000,'Addis Ababa'],
            ['Egypt','EG','africa','EGP',26.82,30.80,48.0,'Medium',105000000,'Cairo'],
            ['DR Congo','CD','africa','USD',-4.03,21.75,78.0,'High',99000000,'Kinshasa'],
            ['Tanzania','TZ','africa','TZS',-6.36,34.89,45.0,'Medium',63000000,'Dodoma'],
            ['Kenya','KE','africa','KES',0.02,37.90,42.0,'Medium',55000000,'Nairobi'],
            ['Algeria','DZ','africa','USD',28.03,1.65,38.0,'Low',45000000,'Algiers'],
            ['Uganda','UG','africa','UGX',1.37,32.29,48.0,'Medium',47000000,'Kampala'],
            ['Ghana','GH','africa','GHS',7.94,-1.02,35.0,'Low',33000000,'Accra'],
            ['Morocco','MA','africa','MAD',31.79,-7.09,30.0,'Low',37000000,'Rabat'],
            ['Nigeria','NG','africa','NGN',9.08,8.67,62.0,'High',218000000,'Abuja'],
            ['Angola','AO','africa','AOA',-11.20,17.87,52.0,'Medium',35000000,'Luanda'],
            ['Cameroon','CM','africa','XAF',3.87,11.50,50.0,'Medium',27000000,'Yaounde'],
            ['Ivory Coast','CI','africa','XOF',7.54,-5.55,45.0,'Medium',27000000,'Yamoussoukro'],
            ['Tunisia','TN','africa','USD',33.88,9.54,38.0,'Low',12000000,'Tunis'],
            ['Libya','LY','africa','USD',26.33,17.22,80.0,'High',7000000,'Tripoli'],
            ['Zimbabwe','ZW','africa','USD',-20.01,30.06,65.0,'High',16000000,'Harare'],
            ['Zambia','ZM','africa','USD',-13.13,27.84,48.0,'Medium',19000000,'Lusaka'],
            ['Senegal','SN','africa','XOF',14.49,-14.45,35.0,'Low',17000000,'Dakar'],
            ['Mozambique','MZ','africa','USD',-18.66,35.53,60.0,'High',32000000,'Maputo'],
            ['Australia','AU','oceania','AUD',-25.27,133.77,10.0,'Very Low',26000000,'Canberra'],
            ['New Zealand','NZ','oceania','NZD',-40.90,174.88,9.0,'Very Low',5100000,'Wellington'],
            ['Papua New Guinea','PG','oceania','USD',-6.31,143.96,52.0,'Medium',9500000,'Port Moresby'],
            ['Fiji','FJ','oceania','USD',-17.71,178.06,30.0,'Low',900000,'Suva'],
        ];

        $classCache = [];
        foreach ($data as [$name,$code,$regionKey,$currencyCode,$lat,$lng,$score,$level,$population,$capital]) {
            if (Country::where('code',$code)->exists()) { continue; }
            $region   = $regionMap[$regionKey] ?? $regionMap['global'];
            $currency = $currencies[$currencyCode] ?? $currencies['USD'];
            $country  = Country::create([
                'name'=>$name,'code'=>$code,'region_id'=>$region->id,'currency_id'=>$currency->id,'capital'=>$capital,
                'latitude'=>$lat,'longitude'=>$lng,'population'=>$population,
                'flag_url'=>'https://flagcdn.com/w320/'.strtolower($code).'.png','languages'=>['local'],
            ]);
            if (!isset($classCache[$level])) {
                $classCache[$level] = RiskClassification::where('name',$level)->first();
            }
            if ($classCache[$level]) {
                RiskScore::create([
                    'country_id'=>$country->id,'classification_id'=>$classCache[$level]->id,
                    'final_risk_score'=>$score,'risk_level'=>$level,
                    'components'=>['weather'=>round($score*0.9,2),'inflation'=>round($score*0.8,2),'currency'=>round($score*0.7,2),'political'=>round($score*1.0,2),'economic'=>round($score*0.85,2),'logistics'=>round($score*0.95,2)],
                    'history'=>[['date'=>now()->subDays(4)->toDateString(),'score'=>$score-1.0],['date'=>now()->subDays(3)->toDateString(),'score'=>$score-0.5],['date'=>now()->subDays(2)->toDateString(),'score'=>$score+0.5],['date'=>now()->subDays(1)->toDateString(),'score'=>$score-0.2],['date'=>now()->toDateString(),'score'=>$score]],
                ]);
            }
            $this->command->line("  Added: $name ($code)");
        }
        $this->command->info('Done! Total countries: '.Country::count());
    }
}
