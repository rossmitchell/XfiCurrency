<?php declare(strict_types=1);
//<editor-fold desc="License Block">
/**
 * Copyright (C) 2019  Ross Mitchell
 *
 * This file is part of RossMitchell\XfiCurrency.
 *
 * RossMitchell\XfiCurrency is free software: you can redistribute
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

namespace RossMitchell\XfiCurrency;

use RossMitchell\XfiCurrency\XFI\XFICurrency;
use Money\Converter as BaseConverter;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Money;

final class Converter
{
    /**
     * @var BaseConverter
     */
    private $converter;
    /**
     * @var Provider
     */
    private $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function toXFI(Money $amount): Money
    {
        return $this->toCurrency($amount, XFICurrency::CODE);
    }

    public function toGbp(Money $amount): Money
    {
        return $this->toCurrency($amount, 'GBP');
    }

    /**
     * At the moment I have to hard code the different fixed values into the converter. As I don't, yet, have a way of
     * converting values from one currency to another, I'm making this method private to make it clear that we only
     * support XFI and GBP. This can be updated if I add in more currency support in the future
     *
     * @param Money  $amount
     * @param string $code
     *
     * @return Money
     */
    private function toCurrency(Money $amount, string $code): Money
    {
        return $this->getConverter()->convert($amount, new Currency($code));
    }

    private function getConverter(): BaseConverter
    {
        if ($this->converter !== null) {
            return $this->converter;
        }

        $exchange        = $this->getExchange();
        $currencies      = $this->provider->getAggregateCurrencies();
        $this->converter = new BaseConverter($currencies, $exchange);

        return $this->converter;
    }

    private function getExchange(): FixedExchange
    {
        return new FixedExchange(
            [
                XFICurrency::CODE => [
                    'GBP' => 0.01,
                ],
                'GBP'             => [
                    XFICurrency::CODE => 100,
                ],
            ]
        );
    }
}
