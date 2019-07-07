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
use RossMitchell\XfiCurrency\Formatter;
use RossMitchell\XfiCurrency\Provider;
use RossMitchell\XfiCurrency\XFI\XFICurrency;

class FormatterTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCurrencyAmounts
     */
    public function itCanFormatXfiMoney(int $amount, array $formats): void
    {
        $baseAmount = $amount;
        $expectedAmount = $formats['XFI'];
        $money = new Money($baseAmount, new Currency(XFICurrency::CODE));
        $formatter = $this->getFormatter();
        $actualResult = $formatter->formatMoney($money);
        self::assertSame($expectedAmount,$actualResult);
    }

    /**
     * @test
     * @dataProvider getCurrencyAmounts
     */
    public function itCanFormatGbpMoney(int $amount, array $formats): void
    {
        $baseAmount = $amount;
        $expectedAmount = $formats['GBP'];
        $money = new Money($baseAmount, new Currency('GBP'));
        $formatter = $this->getFormatter();
        $actualResult = $formatter->formatMoney($money);
        self::assertSame($expectedAmount,$actualResult);
    }

    public function getCurrencyAmounts(): array
    {
        return [
            'It can handle simple amounts' => [12345, ['GBP' => '£123.45', 'XFI' => '123.45']],
            'It can handle large amounts' => [123456789, ['GBP' => '£1,234,567.89', 'XFI' => '1,234,567.89']],
            'It can handle zero' => [0, ['GBP' => '£0.00', 'XFI' => '0.00']],
            'It can handle negative amounts' => [-254, ['GBP' => '-£2.54', 'XFI' => '-2.54']],
        ];
    }

    private function getFormatter(): Formatter
    {
        return new Formatter(new Provider());
    }
}
