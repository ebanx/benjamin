# Benjamin
[![Build Status](https://travis-ci.org/ebanx/benjamin.svg?branch=master)](https://travis-ci.org/ebanx/benjamin)
[![codecov](https://codecov.io/gh/ebanx/benjamin/branch/master/graph/badge.svg)](https://codecov.io/gh/ebanx/benjamin)
[![Latest Stable Version](https://poser.pugx.org/ebanx/benjamin/v/stable?format=flat-square)](https://packagist.org/packages/ebanx/benjamin)
[![Total Downloads](https://poser.pugx.org/ebanx/benjamin/downloads?format=flat-square)](https://packagist.org/packages/ebanx/benjamin)
[![Latest Unstable Version](https://poser.pugx.org/ebanx/benjamin/v/unstable?format=flat-square)](https://packagist.org/packages/ebanx/benjamin)
[![License](https://poser.pugx.org/ebanx/benjamin/license?format=flat-square)](https://packagist.org/packages/ebanx/benjamin)


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
		- [X] :dollar: Spei
	- [X] Chile
		- [X] :dollar: Sencillito
		- [X] :arrows_clockwise: Servipag
		- [X] :arrows_clockwise: Webpay
		- [X] :arrows_clockwise: Multicaja
	- [X] Colombia
		- [X] :arrows_clockwise: PSE
		- [X] :dollar: Baloto
		- [X] :credit_card: Credit Card
	- [X] Peru
		- [X] :arrows_clockwise: SafetyPay
		- [X] :dollar: SafetyPay
		- [X] :dollar: PagoEfectivo
	- [X] Argentina
		- [X] :dollar: Rapipago
		- [X] :dollar: PagoFacil
		- [X] :dollar: Otros Cupones
- [X] Refund
- [X] Payment Capture
- [X] Payment by link (Hosted page gateway)
- [ ] Validator
- [ ] Response Decorators
- [X] Interest Rates
- [X] Taxes

:dollar: = Cash payment  
:credit_card: = Card payment  
:arrows_clockwise: = Online payment  

## License

Copyright 2017 EBANX Payments

Licensed under the Apache License, Version 2.0 (the "License");
you may not use these files except in compliance with the License.
You may obtain a copy of the License at

   [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
