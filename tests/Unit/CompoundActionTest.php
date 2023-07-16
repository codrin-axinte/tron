<?php

namespace Tests\Unit;

use App\Services\CompoundInterestCalculator;
use PHPUnit\Framework\TestCase;

class CompoundActionTest extends TestCase
{
    private CompoundInterestCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = app(CompoundInterestCalculator::class);
    }

    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function it_calculates_compound_correctly_for_an_hour()
    {
        $principal = 100;
        $rate = 10;
        $result = $this->calculator->compoundInterest($principal, $rate);

        $this->assertEquals(110, $result);
    }

    /**
     * @test
     * @return void
     */
    public function it_calculates_compound_correctly_for_hours()
    {
        $principal = 100;
        $rate = 10;
        $hours = 3;

        for ($i = 0; $i < $hours; $i++) {
            $principal = $this->calculator->compoundInterest($principal, $rate);
        }

        $this->assertEquals(133.1, $principal);
    }
}
