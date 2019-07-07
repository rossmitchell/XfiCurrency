XFI Currency
============

XFI Currency is a new currency type for use with [Money PHP](https://github.com/moneyphp/money) that handles values with fractions of pennies.

Installation
------------

```
composer require rossmitchell/xfi-currency
```

Rational
--------

I'm working with financial information related to fund prices. These are returned in fractions of a penny,
e.g. 123.45 is £1,23 pence, and 45 centi-pennies. Money PHP doesn't support these natively using GBP as it expects
whole pennies only.

To avoid having to remember to convert these each time I need to use the data, this package create a new Currency
for use with the Money library that correctly stores the information and then allows the values to be converted
between the two currencies.

I'm using the code XFI, as X is used to denoted things which are "similar to currencies" in ISO-4217, FI to stand
for Financial Information, and XFI as it has not been assigned in the list yet

Usage
-----

Using the currency is as simple as using the code, like so

```php
<?php

use Money\Currency;
use Money\Money;
use RossMitchell\XfiCurrency\XFI\XFICurrency;

$xfiMoney = new Money('12345', new Currency(XFICurrency::CODE));
```

The package also provides a [converter](./src/Converter.php) to convert the currency into GBP and vice versa. This
is currently using a fixed exchange rate between XFI and GBP and can not convert into different currencies. This
can be extended in the future

```php
<?php

use Money\Currency;
use Money\Money;
use RossMitchell\XfiCurrency\Converter;
use RossMitchell\XfiCurrency\Provider;
use RossMitchell\XfiCurrency\XFI\XFICurrency;

$gbp = new Money('123', new Currency('GBP'));
$xfi = new Money('123', new Currency(XFICurrency::CODE));

$converter = new Converter(new Provider());
$convertedXfi = $converter->toXFI($gbp);
$convertedGbp = $converter->toGbp($xfi);
```

The package also provides a formatter for the currency, that wraps around the standard currencies.

```php
<?php

use Money\Currency;
use Money\Money;
use RossMitchell\XfiCurrency\Formatter;
use RossMitchell\XfiCurrency\Provider;
use RossMitchell\XfiCurrency\XFI\XFICurrency;

$gbp = new Money('123', new Currency('GBP'));
$xfi = new Money('123', new Currency(XFICurrency::CODE));

$formatter = new Formatter(new Provider());
echo $formatter->formatMoney($xfi);
echo $formatter->formatMoney($gbp);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[GPL3](https://choosealicense.com/licenses/gpl-3.0/)
