<?php

use Phinx\Seed\AbstractSeed;

class SeedRates extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $rates = array(
            'AUD' => 1.3149,
            'BGN' => 1.7514,
            'BRL' => 3.2848,
            'CAD' => 1.3223,
            'CHF' => 0.97475,
            'CNY' => 6.8137,
            'CZK' => 23.492,
            'DKK' => 6.659,
            'EUR' => 0.8955,
            'GBP' => 0.78314,
            'HKD' => 7.8005,
            'HRK' => 6.6262,
            'HUF' => 275.73,
            'IDR' => 13297,
            'ILS' => 3.5274,
            'INR' => 64.433,
            'JPY' => 111.34,
            'KRW' => 1133.2,
            'MXN' => 17.99,
            'MYR' => 4.2765,
            'NOK' => 8.4727,
            'NZD' => 1.3827,
            'PHP' => 49.802,
            'PLN' => 3.7776,
            'RON' => 4.1045,
            'RUB' => 57.518,
            'SEK' => 8.7194,
            'SGD' => 1.3842,
            'THB' => 33.96,
            'TRY' => 3.5126,
            'ZAR' => 12.88,
            'USD' => 1.000
        );

        $data = array();
        foreach($rates as $code => $rate) {
            $data[] = array(
                'code' => $code,
                'rate' => $rate,
                'created' => date('Y-m-d H:i:s')
            );
        }

        $rates_db = $this->table('rates');
        $rates_db->insert($data)->save();
    }
}
