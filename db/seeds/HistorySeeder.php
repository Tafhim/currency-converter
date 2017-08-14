<?php

use Phinx\Seed\AbstractSeed;

class HistorySeeder extends AbstractSeed
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
        $history_data = array(
            array(
                'from'  => 'AUD',
                'to'    => 'SEK',
                'from_amount'   => '1343',
                'to_amount'     => '8664.357274',
                'created'       => '2017-08-07 12:33:06'
            ),
            array(
                'from'  => 'BRL',
                'to'    => 'AUD',
                'from_amount'   => '1',
                'to_amount'     => '0.403363',
                'created'       => '2017-08-07 12:59:20'
            ),
            array(
                'from'  => 'AUD',
                'to'    => 'BRL',
                'from_amount'   => '1',
                'to_amount'     => '2.479155',
                'created'       => '2017-08-07 12:59:22'
            ),
            array(
                'from'  => 'AUD',
                'to'    => 'CHF',
                'from_amount'   => '2213',
                'to_amount'     => '1685.768881',
                'created'       => '2017-08-11 03:13:26'
            ),
            array(
                'from'  => 'CZK',
                'to'    => 'AUD',
                'from_amount'   => '11100',
                'to_amount'     => '631.796367',
                'created'       => '2017-08-11 03:18:36'
            )
        );

        $history = $this->table('history');
        $history->insert($history_data)->save();
    }
}
