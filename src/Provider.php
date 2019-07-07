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
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;

class Provider
{

    /**
     * @var AggregateCurrencies
     */
    private $aggregateCurrencies;

    public function getAggregateCurrencies(): AggregateCurrencies
    {
        if ($this->aggregateCurrencies !== null) {
            return $this->aggregateCurrencies;
        }
        $this->aggregateCurrencies = new AggregateCurrencies(
            [
                new BitcoinCurrencies(),
                new ISOCurrencies(),
                new XFICurrency(),
            ]
        );

        return $this->aggregateCurrencies;
    }
}
