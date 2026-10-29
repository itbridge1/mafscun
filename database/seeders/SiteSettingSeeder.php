<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSetting::create(
            [
                'title'         => 'Bagmati Proscun',
                'sub_title'     => 'Bagmati Proscun',
                'company_alias' => 'BagmatiCMS',
                'email'         => 'bagmatiproscun@gmail.com',
                'phone'         => '011-665368',
                'location'      => 'Banepa -7',
                'statistics_text'   => 'Members Statistic Details',
                'social'        => [
                    'facebook_link'         => '#',
                    'twitter_link'          => '#',
                    'youtube_link'          => '#',
                    'insta_link'            => '#',
                ],
                'copyright_text'        => 'All Rights Reserved 2022',
                'footer_text'           => '<h3>ABOUT BAGMATI PROSCUN</h3>
                                                <ul>
                                                    <li>
                                                        <a href="#">BAGMATI PROSCUN
                                                            is the member-based national apex organization of Savings and Credit Cooperative
                                                            Societies (SACCOS) & their District Unions (Dus).
                                                        </a>
                                                    </li>
                                                </ul>',
                'powered_by'            => 'IT Bridge Nepal',
                'powered_by_link'       => 'http://itbridge.com.np',
                'keywords'              => 'bagmati, proscun, banepa,Bagmati PROSCUN, PROSCUN, Bagmati ',
                'description'           => 'Bagmati PROSCUN is a common network of savings and credit cooperatives and their district union within 13 districts and 119 local levels within Bagmati',
            ]
        );
    }
}
