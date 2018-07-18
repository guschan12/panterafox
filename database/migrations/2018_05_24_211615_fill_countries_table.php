<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('countries')->insert([
            [
                'name' => 'Argentina',
                'short' => 'AR',
                'flag_link' => '/images/flags/argentina.png'
            ],
            [
                'name' => 'Australia',
                'short' => 'AU',
                'flag_link' => '/images/flags/australia.png'
            ],
            [
                'name' => 'Austria',
                'short' => 'AT',
                'flag_link' => '/images/flags/austria.png'
            ],
            [
                'name' => 'Belarus',
                'short' => 'BY',
                'flag_link' => '/images/flags/belarus.png'
            ],
            [
                'name' => 'Belgium',
                'short' => 'BE',
                'flag_link' => '/images/flags/belgium.png'
            ],
            [
                'name' => 'Brazil',
                'short' => 'BR',
                'flag_link' => '/images/flags/brazil.png'
            ],
            [
                'name' => 'Canada',
                'short' => 'CA',
                'flag_link' => '/images/flags/canada.png'
            ],
            [
                'name' => 'Cuba',
                'short' => 'CU',
                'flag_link' => '/images/flags/cuba.png'
            ],
            [
                'name' => 'Czech',
                'short' => 'CZ',
                'flag_link' => '/images/flags/czech.png'
            ],
            [
                'name' => 'Estonia',
                'short' => 'EE',
                'flag_link' => '/images/flags/estonia.png'
            ],
            [
                'name' => 'France',
                'short' => 'FR',
                'flag_link' => '/images/flags/france.png'
            ],
            [
                'name' => 'Georgia',
                'short' => 'GE',
                'flag_link' => '/images/flags/georgia.png'
            ],
            [
                'name' => 'Germany',
                'short' => 'DE',
                'flag_link' => '/images/flags/germany.png'
            ],
            [
                'name' => 'Greece',
                'short' => 'GR',
                'flag_link' => '/images/flags/greece.png'
            ],
            [
                'name' => 'Israel',
                'short' => 'IL',
                'flag_link' => '/images/flags/israel.png'
            ],
            [
                'name' => 'Italy',
                'short' => 'IT',
                'flag_link' => '/images/flags/italy.png'
            ],
            [
                'name' => 'Kazakhstan',
                'short' => 'KZ',
                'flag_link' => '/images/flags/kazakhstan.png'
            ],
            [
                'name' => 'Lithuania',
                'short' => 'LT',
                'flag_link' => '/images/flags/lithuania.png'
            ],
            [
                'name' => 'Luxembourg',
                'short' => 'LU',
                'flag_link' => '/images/flags/luxembourg.png'
            ],
            [
                'name' => 'Macedonia',
                'short' => 'MK',
                'flag_link' => '/images/flags/macedonia.png'
            ],
            [
                'name' => 'Malaysia',
                'short' => 'MY',
                'flag_link' => '/images/flags/malaysia.png'
            ],
            [
                'name' => 'Moldova',
                'short' => 'MD',
                'flag_link' => '/images/flags/moldova.png'
            ],
            [
                'name' => 'Poland',
                'short' => 'PL',
                'flag_link' => '/images/flags/poland.png'
            ],
            [
                'name' => 'Portugal',
                'short' => 'PT',
                'flag_link' => '/images/flags/portugal.png'
            ],
            [
                'name' => 'Russia',
                'short' => 'RU',
                'flag_link' => '/images/flags/russia.png'
            ],
            [
                'name' => 'Slovakia',
                'short' => 'SK',
                'flag_link' => '/images/flags/slovakia.png'
            ],
            [
                'name' => 'Spain',
                'short' => 'ES',
                'flag_link' => '/images/flags/spain.png'
            ],
            [
                'name' => 'Sweden',
                'short' => 'SE',
                'flag_link' => '/images/flags/sweden.png'
            ],
            [
                'name' => 'Switzerland',
                'short' => 'CH',
                'flag_link' => '/images/flags/switzerland.png'
            ],
            [
                'name' => 'Turkey',
                'short' => 'TR',
                'flag_link' => '/images/flags/turkey.png'
            ],
            [
                'name' => 'United Kingdom',
                'short' => 'GB',
                'flag_link' => '/images/flags/UK.png'
            ],
            [
                'name' => 'United Kingdom',
                'short' => 'GB',
                'flag_link' => '/images/flags/UK.png'
            ],
            [
                'name' => 'Ukraine',
                'short' => 'UA',
                'flag_link' => '/images/flags/ukraine.png'
            ],
            [
                'name' => 'USA',
                'short' => 'US',
                'flag_link' => '/images/flags/USA.png'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('countries')->truncate();
    }
}
