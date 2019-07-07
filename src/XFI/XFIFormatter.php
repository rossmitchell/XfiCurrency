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

use Money\Currencies;
use Money\Exception\FormatterException;
use Money\Money;
use Money\MoneyFormatter;

final class XFIFormatter implements MoneyFormatter
{
    /**
     * @var Currencies
     */
    private $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * Formats a Money object as string.
     *
     * @param Money $money
     *
     * @return string
     *
     * Exception\FormatterException
     */
    public function format(Money $money): string
    {
        if (XFICurrency::CODE !== $money->getCurrency()->getCode()) {
            throw new FormatterException('XFI Formatter can only be used with XFI');
        }

        $valueBase = $money->getAmount();
        $negative = false;

        if (strpos($valueBase, '-') === 0) {
            $negative = true;
            $valueBase = substr($valueBase, 1);
        }

        $subunit = $this->currencies->subunitFor($money->getCurrency());
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted = substr($valueBase, 0, $valueLength - $subunit);

            if ($subunit) {
                $formatted .= '.';
                $formatted .= substr($valueBase, $valueLength - $subunit);
            }
        } else {
            $formatted = '0.' . str_pad('', $subunit - $valueLength, '0') . $valueBase;
        }
        $formatted = number_format((float)$formatted,$subunit);

        if (true === $negative) {
            $formatted = '-' . $formatted;
        }

        return $formatted;
    }
}
