[![Stories in Ready](https://badge.waffle.io/ebanx/benjamin.svg?label=ready&title=Ready)](http://waffle.io/ebanx/benjamin)
[![StyleCI](https://styleci.io/repos/89406660/shield?branch=master)](https://styleci.io/repos/89406660)
[![Build Status](https://travis-ci.org/ebanx/benjamin.svg?branch=master)](https://travis-ci.org/ebanx/benjamin)

# Benjamin

This is the repository for business rules as of implemented by merchant sites for use in e-commerce platform plugins.
The objective is to be a central repository for services and to communicate with the EBANX API (also known as "Pay").

## Getting Started

It is very simple to use Benjamin. You will only need an instance of `Ebanx\Benjamin\Models\Configs\Config` and an instance of `Ebanx\Benjamin\Models\Payment`:

```php
<?php
$config = new Config([
    'sandboxIntegrationKey' => 'YOUR_SANDBOX_INTEGRATION_KEY',
    'isSandbox' => true
]);

$payment = new Payment([
    //Payment properties(see wiki)
]);

$result = Benjamin($config)->create($payment);
```

If you want more information you can check the [Wiki](https://github.com/ebanx/benjamin/wiki/Using-Benjamin).

## Contributing

Check the [Wiki](https://github.com/ebanx/benjamin/wiki/Contributing).

## Checklist for implementations needed

- [ ] Payment
	- [X] Brasil
		- [X] :dollar: Boleto
		- [X] :credit_card: Credit Card
		- [X] :arrows_clockwise: TEF
		- [X] :arrows_clockwise: EBANX Account
	- [ ] Mexico
		- [X] :credit_card: Credit Card
		- [ ] :credit_card: Debit Card
		- [X] :dollar: OXXO
	- [ ] Chile
		- [X] :dollar: Sencillito
		- [ ] :arrows_clockwise: Servipag
	- [XC] Colombia
		- [X] :arrows_clockwise: PSE
		- [X] :dollar: Baloto
		- [X] :credit_card: Credit Card
	- [ ] Peru
		- [ ] :dollar: :arrows_clockwise: SafetyPay
		- [X] :dollar: PagoEfectivo
- [ ] Refund
- [ ] Payment Capture
- [ ] Payment by link
- [ ] Validator
- [ ] Response Decorators
- [ ] Notifications
- [ ] Interest Rates
- [ ] Taxes

:dollar: = Cash payment
:credit_card: = Card payment
:arrows_clockwise: = Online payment
