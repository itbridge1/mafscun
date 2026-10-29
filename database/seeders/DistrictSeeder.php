<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('districts')->truncate();
        Schema::enableForeignKeyConstraints();

        $province_1 = [
            [
                'province_id' => 1,
                'name' => 'Bhojpur',
                'nepali_name' => 'भोजपुर',
                'headquarter' => 'Bhojpur',
                'area' => 1507,
            ],
            [
                'province_id' => 1,
                'name' => 'Dhankuta',
                'nepali_name' => 'धनकुटा',
                'headquarter' => 'Dhankuta',
                'area' => 892,
            ],
            [
                'province_id' => 1,
                'name' => 'Ilam',
                'nepali_name' => 'ईलाम',
                'headquarter' => 'Ilam',
                'area' => 1703,
            ],
            [
                'province_id' => 1,
                'name' => 'Jhapa',
                'nepali_name' => 'झापा',
                'headquarter' => 'Bhadrapur',
                'area' => 1606,
            ],
            [
                'province_id' => 1,
                'name' => 'Khotang',
                'nepali_name' => 'खोटाङ',
                'headquarter' => 'Diktel',
                'area' => 1591,
            ],
            [
                'province_id' => 1,
                'name' => 'Morang',
                'nepali_name' => 'मोरङ',
                'headquarter' => 'Biratnagar',
                'area' => 1855,
            ],
            [
                'province_id' => 1,
                'name' => 'Okhaldhunga',
                'nepali_name' => 'ओखल ढुङ्गा',
                'headquarter' => 'Siddhicharan',
                'area' => 1074,
            ],
            [
                'province_id' => 1,
                'name' => 'Panchthar',
                'nepali_name' => 'पाँचथर',
                'headquarter' => 'Phidim',
                'area' => 1241,
            ],
            [
                'province_id' => 1,
                'name' => 'Sankhuwasabha',
                'nepali_name' => 'संखुवासभा',
                'headquarter' => 'Khandbari',
                'area' => 3480,
            ],
            [
                'province_id' => 1,
                'name' => 'Solukhumbu',
                'nepali_name' => 'सोलुखुम्बु',
                'headquarter' => 'Salleri',
                'area' => 3312,
            ],
            [
                'province_id' => 1,
                'name' => 'Sunsari',
                'nepali_name' => 'सुन्सरी',
                'headquarter' => 'Inaruwa',
                'area' => 1257,
            ],
            [
                'province_id' => 1,
                'name' => 'Taplejung',
                'nepali_name' => 'ताप्लेजुङ',
                'headquarter' => 'Taplejung',
                'area' => 3646,
            ],
            [
                'province_id' => 1,
                'name' => 'Terhathum',
                'nepali_name' => 'तेह्रथुम',
                'headquarter' => 'Myanglung',
                'area' => 679,
            ],
            [
                'province_id' => 1,
                'name' => 'Udayapur',
                'nepali_name' => 'उदयपुर',
                'headquarter' => 'Gaighat',
                'area' => 2063,
            ],
        ];

        $province_2 = [
            [
                'province_id' => 2,
                'name' => 'Parsa',
                'nepali_name' => 'पर्सा',
                'headquarter' => 'Birganj',
                'area' => 1353,
            ],
            [
                'province_id' => 2,
                'name' => 'Bara',
                'nepali_name' => 'बारा',
                'headquarter' => 'Kataiya',
                'area' => 1190,
            ],
            [
                'province_id' => 2,
                'name' => 'Rautahat',
                'nepali_name' => 'रौतहट',
                'headquarter' => 'Gaur',
                'area' => 1126,
            ],
            [
                'province_id' => 2,
                'name' => 'Sarlahi',
                'nepali_name' => 'सर्लाही',
                'headquarter' => 'Malangwa',
                'area' => 1259,
            ],
            [
                'province_id' => 2,
                'name' => 'Dhanusha',
                'nepali_name' => 'धनुषा',
                'headquarter' => 'Janakpur',
                'area' => 1180,
            ],
            [
                'province_id' => 2,
                'name' => 'Siraha',
                'nepali_name' => 'सिरहा',
                'headquarter' => 'Siraha',
                'area' => 1188,
            ],
            [
                'province_id' => 2,
                'name' => 'Mahottari',
                'nepali_name' => 'महोत्तरी',
                'headquarter' => 'Jaleshwar',
                'area' => 1002,
            ],
            [
                'province_id' => 2,
                'name' => 'Saptari',
                'nepali_name' => 'सप्तरी',
                'headquarter' => 'Rajbiraj',
                'area' => 1363,
            ],
        ];

        $province_3 = [
            [
                'province_id' => 3,
                'name' => 'Sindhuli',
                'nepali_name' => 'सिन्धुली',
                'headquarter' => 'Kamalamai',
                'area' => 2491,
            ],
            [
                'province_id' => 3,
                'name' => 'Ramechhap',
                'nepali_name' => 'रामेछाप',
                'headquarter' => 'Manthali',
                'area' => 1546,
            ],
            [
                'province_id' => 3,
                'name' => 'Dolakha',
                'nepali_name' => 'दोलखा',
                'headquarter' => 'Bhimeshwar',
                'area' => 2191,
            ],
            [
                'province_id' => 3,
                'name' => 'Bhaktapur',
                'nepali_name' => 'भक्तपुर',
                'headquarter' => 'Bhaktapur',
                'area' => 1359,
            ],
            [
                'province_id' => 3,
                'name' => 'Dhading',
                'nepali_name' => 'धादिङ',
                'headquarter' => 'Nilkantha',
                'area' => 1926,
            ],
            [
                'province_id' => 3,
                'name' => 'Kathmandu',
                'nepali_name' => 'काठमाडौं',
                'headquarter' => 'Kathmandu',
                'area' => 395,
            ],
            [
                'province_id' => 3,
                'name' => 'Kavrepalanchok',
                'nepali_name' => 'काभ्रेपलान्चोक',
                'headquarter' => 'Dhulikhel',
                'area' => 1003,
            ],
            [
                'province_id' => 3,
                'name' => 'Lalitpur',
                'nepali_name' => 'ललितपुर',
                'headquarter' => 'Lalitpur',
                'area' => 385,
            ],
            [
                'province_id' => 3,
                'name' => 'Nuwakot',
                'nepali_name' => 'नुवाकोट',
                'headquarter' => 'Bidur',
                'area' => 1121,
            ],
            [
                'province_id' => 3,
                'name' => 'Rasuwa',
                'nepali_name' => 'रसुवा',
                'headquarter' => 'Dhunche',
                'area' => 1544,
            ],
            [
                'province_id' => 3,
                'name' => 'Sindhupalchok',
                'nepali_name' => 'सिन्धुपाल्चोक',
                'headquarter' => 'Chautara',
                'area' => 2542,
            ],
            [
                'province_id' => 3,
                'name' => 'Chitwan',
                'nepali_name' => 'चितवन',
                'headquarter' => 'Bharatpur',
                'area' => 2218,
            ],
            [
                'province_id' => 3,
                'name' => 'Makawanpur',
                'nepali_name' => 'मकवानपुर',
                'headquarter' => 'Hetauda',
                'area' => 2426,
            ],
        ];

        $province_4 = [
            [
                'province_id' => 4,
                'name' => 'Baglung',
                'nepali_name' => 'बाग्लुङ',
                'headquarter' => 'Baglung',
                'area' => 1784,
            ],
            [
                'province_id' => 4,
                'name' => 'Gorkha',
                'nepali_name' => 'गोर्खा',
                'headquarter' => 'Gorkha',
                'area' => 3610,
            ],
            [
                'province_id' => 4,
                'name' => 'Kaski',
                'nepali_name' => 'कास्की',
                'headquarter' => 'Pokhara',
                'area' => 2017,
            ],
            [
                'province_id' => 4,
                'name' => 'Lamjung',
                'nepali_name' => 'लम्जुङ',
                'headquarter' => 'Besisahar',
                'area' => 1692,
            ],
            [
                'province_id' => 4,
                'name' => 'Manang',
                'nepali_name' => 'मनाङ',
                'headquarter' => 'Chame',
                'area' => 2246,
            ],
            [
                'province_id' => 4,
                'name' => 'Mustang',
                'nepali_name' => 'मुस्ताङ',
                'headquarter' => 'Jomsom',
                'area' => 3573,
            ],
            [
                'province_id' => 4,
                'name' => 'Myagdi',
                'nepali_name' => 'म्याग्दी',
                'headquarter' => 'Beni',
                'area' => 2297,
            ],
            [
                'province_id' => 4,
                'name' => 'Nawalpur',
                'nepali_name' => 'नवलपुर',
                'headquarter' => 'Kawasoti',
                'area' => 1043,
            ],
            [
                'province_id' => 4,
                'name' => 'Parbat',
                'nepali_name' => 'पर्बत',
                'headquarter' => 'Kusma',
                'area' => 494,
            ],
            [
                'province_id' => 4,
                'name' => 'Syangja',
                'nepali_name' => 'स्याङ्जा',
                'headquarter' => 'Putalibazar',
                'area' => 1164,
            ],
            [
                'province_id' => 4,
                'name' => 'Tanahu',
                'nepali_name' => 'तनहुँ',
                'headquarter' => 'Damauli',
                'area' => 1546,
            ],
        ];

        $province_5 = [
            [
                'province_id' => 5,
                'name' => 'Kapilvastu',
                'nepali_name' => 'कपिलवस्तु',
                'headquarter' => 'Taulihawa',
                'area' => 1738,
            ],
            [
                'province_id' => 5,
                'name' => 'Parasi',
                'nepali_name' => 'परासी',
                'headquarter' => 'Ramgram',
                'area' => 635,
            ],
            [
                'province_id' => 5,
                'name' => 'Rupandehi',
                'nepali_name' => 'रुपन्देही',
                'headquarter' => 'Siddarthanagar',
                'area' => 1360,
            ],
            [
                'province_id' => 5,
                'name' => 'Arghakhanchi',
                'nepali_name' => 'अर्घाखाची',
                'headquarter' => 'Sandhikharka',
                'area' => 1193,
            ],
            [
                'province_id' => 5,
                'name' => 'Gulmi',
                'nepali_name' => 'गुल्मी',
                'headquarter' => 'Tamghas',
                'area' => 1149,
            ],
            [
                'province_id' => 5,
                'name' => 'Palpa',
                'nepali_name' => 'पाल्पा',
                'headquarter' => 'Tansen',
                'area' => 1373,
            ],
            [
                'province_id' => 5,
                'name' => 'Dang',
                'nepali_name' => 'दाङ',
                'headquarter' => 'Ghorahi',
                'area' => 2955,
            ],
            [
                'province_id' => 5,
                'name' => 'Pyuthan',
                'nepali_name' => 'प्युठान',
                'headquarter' => 'Pyuthan',
                'area' => 1309,
            ],
            [
                'province_id' => 5,
                'name' => 'Rolpa',
                'nepali_name' => 'रोल्पा',
                'headquarter' => 'Liwang',
                'area' => 1879,
            ],
            [
                'province_id' => 5,
                'name' => 'Eastern Rukum',
                'nepali_name' => 'पूर्व रुकुम',
                'headquarter' => 'Rumukot',
                'area' => 1161,
            ],
            [
                'province_id' => 5,
                'name' => 'Banke',
                'nepali_name' => 'बाँके',
                'headquarter' => 'Nepalgunj',
                'area' => 2337,
            ],
            [
                'province_id' => 5,
                'name' => 'Bardiya',
                'nepali_name' => 'बर्दिया',
                'headquarter' => 'Gulariya',
                'area' => 2337,
            ],
        ];

        $province_6 = [
            [
                'province_id' => 6,
                'name' => 'Western Rukum',
                'nepali_name' => 'पश्चिम रुकुम',
                'headquarter' => 'Musikot',
                'area' => 1213,
            ],
            [
                'province_id' => 6,
                'name' => 'Salyan',
                'nepali_name' => 'सल्यान',
                'headquarter' => 'Salyan',
                'area' => 1462,
            ],
            [
                'province_id' => 6,
                'name' => 'Dolpa',
                'nepali_name' => 'डोल्पा',
                'headquarter' => 'Dunai',
                'area' => 7889,
            ],
            [
                'province_id' => 6,
                'name' => 'Humla',
                'nepali_name' => 'हुम्ला',
                'headquarter' => 'Simikot',
                'area' => 5655,
            ],
            [
                'province_id' => 6,
                'name' => 'Jumla',
                'nepali_name' => 'जुम्ला',
                'headquarter' => 'Chandannath',
                'area' => 2531,
            ],
            [
                'province_id' => 6,
                'name' => 'Kalikot',
                'nepali_name' => 'कालिकोट',
                'headquarter' => 'Manma',
                'area' => 1741,
            ],
            [
                'province_id' => 6,
                'name' => 'Mugu',
                'nepali_name' => 'मुगू',
                'headquarter' => 'Gamgadhi',
                'area' => 3535,
            ],
            [
                'province_id' => 6,
                'name' => 'Surkhet',
                'nepali_name' => 'सुर्खेत',
                'headquarter' => 'Birendranagar',
                'area' => 2461,
            ],
            [
                'province_id' => 6,
                'name' => 'Dailekh',
                'nepali_name' => 'दैलेख',
                'headquarter' => 'Narayan',
                'area' => 1502,
            ],
            [
                'province_id' => 6,
                'name' => 'Jajarkot',
                'nepali_name' => 'जाजरकोट',
                'headquarter' => 'Khalanga',
                'area' => 2230,
            ],
        ];

        $province_7 = [
            [
                'province_id' => 7,
                'name' => 'Kailali',
                'nepali_name' => 'कैलाली',
                'headquarter' => 'Dhangadi',
                'area' => 3235,
            ],
            [
                'province_id' => 7,
                'name' => 'Achham',
                'nepali_name' => 'अछाम',
                'headquarter' => 'Mangalsen',
                'area' => 1680,
            ],
            [
                'province_id' => 7,
                'name' => 'Doti',
                'nepali_name' => 'डोटी',
                'headquarter' => 'Dipayal Silgadhi',
                'area' => 2025,
            ],
            [
                'province_id' => 7,
                'name' => 'Bajhang',
                'nepali_name' => 'बझाङ',
                'headquarter' => 'Jayaprithvi',
                'area' => 3422,
            ],
            [
                'province_id' => 7,
                'name' => 'Bajura',
                'nepali_name' => 'बाजुरा',
                'headquarter' => 'Martadi',
                'area' => 2188,
            ],
            [
                'province_id' => 7,
                'name' => 'Kanchanpur',
                'nepali_name' => 'कंचनपुर',
                'headquarter' => 'Bhimdatta',
                'area' => 1610,
            ],
            [
                'province_id' => 7,
                'name' => 'Dadeldhura',
                'nepali_name' => 'डडेल्धुरा',
                'headquarter' => 'Amargadhi',
                'area' => 1538,
            ],
            [
                'province_id' => 7,
                'name' => 'Baitadi',
                'nepali_name' => 'बैतडी',
                'headquarter' => 'Dasharathchand',
                'area' => 1519,
            ],
            [
                'province_id' => 7,
                'name' => 'Darchula',
                'nepali_name' => 'दार्चुला',
                'headquarter' => 'Darchula',
                'area' => 2322,
            ],
        ];

        $districts = array_merge(
            $province_1,
            $province_2,
            $province_3,
            $province_4,
            $province_5,
            $province_6,
            $province_7,
        );

        DB::table('districts')->insert($districts);
    }
}
