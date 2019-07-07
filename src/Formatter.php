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

use RossMitchell\XfiCurrency\XFI\XFIFormatter;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class Formatter
{
    /**
     * @var Provider
     */
    private $provider;
    /**
     * @var AggregateMoneyFormatter
     */
    private $aggregateFormatter;
    /**
     * @var string
     */
    private $locale;

    public function __construct(Provider $provider, string $locale = 'en_GB')
    {
        $this->provider = $provider;
        $this->locale   = $locale;
    }

    public function formatMoney(Money $amount): string
    {
        return $this->getFormatter()->format($amount);
    }

    private function getFormatter(): AggregateMoneyFormatter
    {
        if ($this->aggregateFormatter !== null) {
            return $this->aggregateFormatter;
        }

        $currencies = $this->provider->getAggregateCurrencies();

        $xfiFormatter             = new XFIFormatter($currencies);
        $numberFormatter          = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $moneyFormatter           = new IntlMoneyFormatter($numberFormatter, $currencies);
        $bitcoinFormatter         = new BitcoinMoneyFormatter(2, $currencies);
        $this->aggregateFormatter = new AggregateMoneyFormatter(
            [
                'XFI' => $xfiFormatter,
                'XBT' => $bitcoinFormatter,
                '*'   => $moneyFormatter,
            ]
        );

        return $this->aggregateFormatter;
    }
}
