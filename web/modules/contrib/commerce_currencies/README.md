# Commerce Currencies

**We're still very early in the development. Please, don't simply install the module to an existing store
with products and prices already set up without making a database backup first!**

Stock Commerce allows you to specify multiple currencies but only allows to specify one price per product
(from any of those currencies enabled). This module allows you to add prices in each supported currency.

The aim of this module is to provide a seamless, out-of-the-box support for multiple currencies.
You don't need to modify the structure of your existing products, everything is handled automatically.

In order the achieve this, the module does the following: when installed, it checks the existing
commerce entities and, if a stock `commerce_price` field is found, it gets replaced with
a multi-currency variant. Widgets (single or list price) and display settings are also replaced
with the corresponding multi-currency variant. Previous prices will be migrated to the new
multi-currency setup automatically. This way, your existing UI will support multiple currencies immediately,
without any change on your part. You don't have to add new fields, neither a common one for all currencies
nor one per each new currency.

If you happen to add new product variations later, the same automatic process will set them up for
multi-currency functioning as well. It only happens with new variations, editing existing ones
is up to you.

Having said that, you're certainly free to use the new field type, its widgets and formatters manually,
if you prefer to do so, or, maybe, you use `commerce_price` fields in your own entities or code that the
module will not handle automatically. The id of the new field type is `commerce_currencies_price`
and you'll also find it in the usual field UI among the Commerce related field types as _Price (multi-currency)_.
If you have your own, customized theme templates naming the old price fields specifically,
you will also need to fix those.

The actual prices will be stored in separate database entries, the original fields in the relevant
`commerce_` tables are simply left untouched, unused.

## Installation

Considering the automated changes described above, **it is emphatically recommended to make a backup**
of your database before installation, unless you are setting up a new store from scratch, anyway.

Install and enable the module through Composer:
```
composer require drupal/commerce_currencies
drush en commerce_currencies
```

## Settings

Price fields have new possibilities (eg. whether to always show all currency prices or only the
currently selected one), so you may want to check and change those as you prefer.

The module doesn't provide any other resolution mechanism (like rate exchange) of its own,
it uses other resolvers (eg. [Commerce Exchanger](https://www.drupal.org/project/commerce_exchanger))
installed in the system if instructed to do so and a particular currency price is missing for a product.

The module also provides a switcher block that you can place anywhere to allow your visitors to select
their preferred currency. This block is only available in the normal part of the website and when viewing
the cart but already gets suppressed for the checkout process — changing the currency that late in the
ordering process is not something that can be handled reliably. If you need this, you should instruct
your users to go back to the cart and start the process again.

You can also add a _Preferred currency_ field to the user profiles instead and it will be discovered
and used automatically.

It is very much recommended to specify separate taxes, promotions, shipping methods and similar,
each limited to specific currencies using the _Current currency_ condition in their settings
if they are supposed to work differently depending on the actual currency (as opposed to, for instance,
a simple percentage of the total amount, that can be calculated regardless of currency).
**IMPORTANT: use this new one, not the original Order currency. The order currency only becomes valid
at the end of the order recalculations, not during the process!**

If you need different tax rates for different product types, we recommend using our
[Commerce Tax Rate](https://www.drupal.org/project/commerce_tax_rate) module as well.

## Uninstallation

The uninstallation uses a special sequence that first reverts the automatic changes described above,
trying to make sure that the system returns to the single-currency Commerce functioning. However,
note that other settings (eg. views and blocks) might also refer to the multi-currency fields
or their underlying database tables. These will produce errors and will have to be fixed
after you uninstall Commerce Currencies.

**Please, make sure you do all the precautions first and you are prepared with backup copies
of your database BEFORE you start the uninstallation.**

## To-do

*