Yii2 extension for Nova Poshta API
==================================

 > IMPORTANT! Extension is already under development

Extension to work with Nova Poshta API

[![Latest Stable Version](https://poser.pugx.org/joni-jones/yii2-novaposhta/v/stable)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/?branch=master)
[![Total Downloads](https://poser.pugx.org/joni-jones/yii2-novaposhta/downloads)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Build Status](https://img.shields.io/travis/joni-jones/yii2-novaposhta.svg)](http://travis-ci.org/joni-jones/yii2-novaposhta)
[![License](https://poser.pugx.org/joni-jones/yii2-novaposhta/license)](https://packagist.org/packages/joni-jones/yii2-novaposhta)
[![Code Coverage](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/joni-jones/yii2-novaposhta/?branch=master)

### Usage

All models methods and properties has the same as in official documentation.

1. Setup configuration:
```php
'components' => [
    'api_key' => 'specify your api key',
    'format' => 'json' \\supported json and xml formats
]
```
2. Create new api model:
```php
$model = new \jones\novaposhta\Address(new \jones\novaposhta\request\Factory());
```
or
```php
$model = Yii::createObject(\jones\novaposhta\Address::class);
```
in the second case request factory will be created by Yii DI manager.
3. Process request:
```php
$areas = $model->getAreas();
```

Each model has list of rules where described attributes per each scenario. If some api call will be processed without required attributes you will get `false` instead normal response.
To get list of errors just call:
```php
$model->getErrors();
```

### List of available models

 - Address
 - InternetDocument (under development)
 - Common (under development)
 - ContactPerson (under development)
 - Counterparty (under development)
 - ScanSheet (under development)

### List of implemented methods

#### Address model

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