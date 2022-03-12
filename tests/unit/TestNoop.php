<?php
namespace Tests\unit;

use DateTime; // used to create date
use SocialPost\Dto\SocialPostTo; // used to crete post
use Statistics\Builder\ParamsBuilder; // used to create parameters that the function checkPost needs before accumulating the data
use Statistics\Calculator\NoopCalculator; // used to test noopcalculator

use PHPUnit\Framework\TestCase;

/**
 * @package Tests\unit
 */
class TestNoop extends TestCase
{
    /**
     * @testnoop
     */

    // test created to verify if the average is correctly calculated. I send a controlled number of posts to noopcalculator and test if the outcome is the correct one by comparison
    public function testNoopCalculator()
    {
        $date = new DateTime();
        $params = ParamsBuilder::reportStatsParams($date);
        $noopcalculator = new NoopCalculator();

        // index 3 because ParamsBuilder::reportStatsParams will find the parameters for the different calculations and average posts per user per month is in the index of 3
        $calc = $noopcalculator->setParameters($params[3]);

        // created an array of 7 different authorsid with 3 diferent authors (1, 2 and 3)
        $postAuthors = [1, 2, 1, 1, 2, 3, 3];

        // I called the function accumulateData for each index in the array. I called accumulateData in the abstractcalculator so I could call the protected function doAccumulate in the noopcalculator
        foreach ($postAuthors as $key => $val){
            $calc->accumulateData( (new SocialPostTo())->setId($key)->setAuthorId($val)->setText("random post text")->setDate($date) );
        }

        // I called the function calculate in the abstractcalculator so I could call the protected function doCalculate in the noopcalculator
        $average = $calc->calculate();

        // this tests if the my function will actually divide 7 by 3 resulting in 2.3333333... -> rounding to 2.33
        $this->assertEquals($average->getValue(), 2.33);
    }

}