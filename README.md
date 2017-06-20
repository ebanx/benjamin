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
    'integrationKey' => 'YOUR_INTEGRATION_KEY',
    'sandboxIntegrationKey' => 'YOUR_SANDBOX_INTEGRATION_KEY'
]);

$payment = new Payment([
    //Payment properties(see wiki)
]);

$result = EBANX($config)->create($payment);
```

If you want more information you can check the [Wiki](https://github.com/ebanx/benjamin/wiki/Using-Benjamin).

## Contributing

Check the [Wiki](https://github.com/ebanx/benjamin/wiki/Contributing).

## Checklist for implementations needed

- [X] Payment
	- [X] Brasil
		- [X] :dollar: Boleto
		- [X] :credit_card: Credit Card
		- [X] :arrows_clockwise: TEF
		- [X] :arrows_clockwise: EBANX Account
	- [X] Mexico
		- [X] :credit_card: Credit Card
		- [X] :credit_card: Debit Card
		- [X] :dollar: OXXO
	- [X] Chile
		- [X] :dollar: Sencillito
		- [X] :arrows_clockwise: Servipag
	- [X] Colombia
		- [X] :arrows_clockwise: PSE
		- [X] :dollar: Baloto
		- [X] :credit_card: Credit Card
	- [X] Peru
		- [X] :arrows_clockwise: SafetyPay
		- [X] :dollar: SafetyPay
		- [X] :dollar: PagoEfectivo
- [X] Refund
- [X] Payment Capture
- [X] Payment by link
- [ ] Validator
- [ ] Response Decorators
- [ ] Notifications
- [X] Interest Rates
- [X] Taxes
- [ ] Card brand detector

:dollar: = Cash payment  
:credit_card: = Card payment  
:arrows_clockwise: = Online payment  
