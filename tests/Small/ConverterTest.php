<?php declare(strict_types=1);
//<editor-fold desc="License Block">
/**
 * Copyright (C) 2019  Ross Mitchell
 *
 * This file is part of Ross Mitchell/xfi-currency.
 *
 * Ross Mitchell/xfi-currency is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
//</editor-fold>

namespace RossMitchell\XfiCurrency\Tests\Small;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use RossMitchell\XfiCurrency\Converter;
use RossMitchell\XfiCurrency\Provider;
use RossMitchell\XfiCurrency\XFI\XFICurrency;

/**
 * @small
 * @testdox RossMitchell\XfiCurrency\Converter
 * @coversDefaultClass \RossMitchell\XfiCurrency\Converter
 */
class ConverterTest extends TestCase
{
    /**
     * @test
     * @dataProvider getComplexCurrencyPairs
     */
    public function itCanConvertXfiToGbp(int $xfiAmount, int $gbpAmount):void
    {
        $baseAmount = $xfiAmount;
        $expectedAmount = (string) $gbpAmount;
        $money = new Money($baseAmount, new Currency(XFICurrency::CODE));
        $converter = $this->getConverter();
        $convertedMoney = $converter->toGbp($money);

        self::assertSame($expectedAmount, $convertedMoney->getAmount());
    }

    /**
     * @test
     * @dataProvider getSimpleCurrencyPairs
     */
    public function itCanConvertGbpToXfi(int $xfiAmount, int $gbpAmount):void
    {
        $baseAmount = $gbpAmount;
        $expectedAmount = (string) $xfiAmount;
        $money = new Money($baseAmount, new Currency('GBP'));
        $converter = $this->getConverter();
        $convertedMoney = $converter->toXFI($money);

        self::assertSame($expectedAmount, $convertedMoney->getAmount());
    }

    public function getSimpleCurrencyPairs(): array
    {
        return [
            'It can handle small numbers' => [100, 1],
            'It can handle larger numbers' => [10000, 100],
            'It can handle zero' => [0,0],
            'It can handle negative numbers' => [-100, -1],
        ];
    }

    public function getComplexCurrencyPairs(): array
    {
        $simple = $this->getSimpleCurrencyPairs();
        $complex = [
            'It will round down when needed' => [10012, 100],
            'It will round up when needed' => [10089, 101]
        ];

        return array_merge($simple,$complex);
    }

    private function getConverter(): Converter
    {
        return new Converter(new Provider());
    }
}
