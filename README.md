Yii2 extension for Nova Poshta API
==================================

 > IMPORTANT! Extension is under development

Extension to work with Nova Poshta API

[![Latest Stable Version](https://poser.pugx.org/joni-jones/yii2-novaposhta/v/stable)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/?branch=master)
[![Total Downloads](https://poser.pugx.org/joni-jones/yii2-novaposhta/downloads)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Build Status](https://img.shields.io/travis/joni-jones/yii2-novaposhta.svg)](http://travis-ci.org/joni-jones/yii2-novaposhta)
[![License](https://poser.pugx.org/joni-jones/yii2-novaposhta/license)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Code Coverage](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/?branch=master)

### Installation via Composer

Since this is a fork repository, and original repo is not available on Packagist, you need to add this to your `composer.json`

```
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Webkadabra/yii2-novaposhta"
    }
  ],
```

After that, you can install package with a command:

> composer require webkadabra/yii2-novaposhta

### Setup

All models methods and properties has the same as in official documentation.

1. Setup configuration:
```php
'components' => [
    'novaposhta' => [
        'class' => 'jones\novaposhta\Api',
        'api_key' => 'specify your api key',
        'format' => 'json' \\supported json and xml formats
    ]
]
```
The json format more appreciated, because response formatting faster.

2. Set alias for extension in `bootstrap.php` file:
```php
Yii::setAlias('novaposhta', '@vendor/webkadabra/yii2-novaposhta');
```

Or, if you use Yii2 Advanced template, add this to your `common/config/bootstrap.php`
```
Yii::setAlias('novaposhta', dirname(dirname(__DIR__)) . '/vendor/webkadabra/yii2-novaposhta');

```
3. Configure extension translations:
```php
'components' => [
    'i18n' => [
        'translations' => [
            'api' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@novaposhta/messages',
                'sourceLanguage' => 'en',
            ],
        ],
    ],
]
```

4. Create new api model:
```php
$model = new \jones\novaposhta\Address(new \jones\novaposhta\request\RequestFactory());
```
or
```php
$model = Yii::createObject(\jones\novaposhta\Address::class);
```
in the second case request factory will be created by Yii DI manager.

5. Process request:
```php
$areas = $model->getAreas();
```

Each model has list of rules where described attributes per each scenario. If some api call will be processed without required attributes you will get `false` instead normal response.
To get list of errors just call:
```php
$model->getErrors();
```

### List of available models

 - [Address](#address-model)
 - InternetDocument (under development)
 - Common (under development)
 - ContactPerson (under development)
 - Counterparty (under development)
 - ScanSheet (under development)

### List of implemented methods

#### <a name="address-model"></a>Address model

 - getAreas
    
    ```php
    $areas = $addressModel->getAreas();
    ```
 - getCities
    
    ```php
    $cities = $addressModel->getCities('Бровари'); // filter not empty add it to `FindByString` request param
    ```
 - delete
    
    ```php
    $addressModel->Ref = 'fs1d2vbv12'; // if Ref is not specified you will get validation error
    $addressModel->delete();
    ```
 - getWarehouses
    
    ```php
    $addressModel->CityRef = 'df1j2cmf5d';  // CityRef is required parameter
    $warehouses = $addressModel->getWarehouses('Броварський'); // Street name is additional parameter
    ```
 - getStreet
    
    ```php
    $streets = $addressModel->getStreet('city ref', 'street name');
    ```
 - getWarehouseTypes
    
    ```php
    $types = $addressModel->getWarehouseTypes();
    ```
 - save
    
    ```php
    $addressModel->StreetRef = 'd8364179-4149-11dd-9198-001d60451983';
    $addressModel->CounterpartyRef = '56300fb9-cbd3-11e4-bdb5-005056801329';
    $response = $addressModel->save('10', 12, 'Comment');   // building should be in string type
    ```
 - update
    
    ```php
    $addressModel->Ref = '503702df-cd4c-11e4-bdb5-005056801329';
    $addressModel->CounterpartyRef = '56300fb9-cbd3-11e4-bdb5-005056801329';
    $response = $addressModel->save('10', 12, 'Comment');   // building should be in string type
    ```

#### Counterparty model

 - getAreas
    
    ```php
    $counterparties = $counterpartyModel->getCounterparties();
    ```
 - getCounterpartyAddresses
 
    ```php
    $addresses = $counterpartyModel->getCounterpartyAddresses('6e9acced-d072-11e3-95eb-0050568046cd', 'Sender');
    ```
 - getCounterpartyContactPersons
 
    ```php
    $persons = $counterpartyModel->getCounterpartyContactPersons('6e9acced-d072-11e3-95eb-0050568046cd');
    ```
 - save

    ```php
    // set model attributes
    $counterpartyModel->cityRef = '0050568046cd';
    .
    .
    .
    $counterpartyModel->Phone = '0567894512';
    $response = $counterpartyModel->save();
    ```
 - update

    ```php
    // set model attributes
    $counterpartyModel->Ref = '005056801329';
    .
    .
    .
    $counterpartyModel->Email = 'contractor@test.com';
    $response = $counterpartyModel->update();
    ```
 - delete
    
    ```php
    $counterparty->Ref = '0050568002cf'; // if Ref is not specified you will get validation error
    $counterparty->delete();
    ```
