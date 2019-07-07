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

namespace RossMitchell\XfiCurrency\XFI;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Money\Money;
use Traversable;

/**
 * The financial information that I am using is returned in fractions of a penny, e.g. 123.45 is £1, 23 pence, and 45
 * centi-pennies. The money lib on the other hand only supports entire pennies, and turns them from decimals into ints.
 *
 * To avoid having to remember to convert these each time I need to use the data, we will create a new Currency for use
 * with the Money library that correctly stores the information and then allows the values to be converted between the
 * two currencies.
 *
 * I'm using the code XFI, as X is used to denoted things which are "similar to currencies" in ISO-4217, FI to stand
 * for Financial Information, and XFI as it has not been assigned in the list yet
 */
final class XFICurrency implements Currencies
{
    public const CODE = 'XFI';

    /**
     * Retrieve an external iterator
     *
     * @link  https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([new Currency(self::CODE)]);
    }

    /**
     * Checks whether a currency is available in the current context.
     *
     * @param Currency $currency
     *
     * @return bool
     */
    public function contains(Currency $currency): bool
    {
        return self::CODE === $currency->getCode();
    }

    /**
     * Returns the subunit for a currency.
     *
     * @param Currency $currency
     *
     * @return int
     *
     * @throws UnknownCurrencyException If currency is not available in the current context
     */
    public function subunitFor(Currency $currency): int
    {
        if ($currency->getCode() !== self::CODE) {
            throw new UnknownCurrencyException(
                $currency->getCode() . ' is not bitcoin and is not supported by this currency repository'
            );
        }

        return 2;
    }
}
