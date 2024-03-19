# Zoho Laravel API V6 Package

![Packagist Version](https://img.shields.io/packagist/v/optimacloud/zoho-laravel)
![Packagist Downloads](https://img.shields.io/packagist/dt/optimacloud/zoho-laravel)

This package used to integrate with the Zoho V6 Api CRM

## Requirements

* PHP >= 8.0
* Laravel >= 8.0

## Registering a Zoho Client

Since Zoho CRM APIs are authenticated with OAuth2 standards, you should register your client app with Zoho. To register
your app:

1. Visit this page [https://api-console.zoho.com/](https://api-console.zoho.com)
2. Click on `ADD CLIENT`.
3. Choose a `Self Client`.
4. Create grant token by providing the necessary scopes, time duration (the duration for which the generated token is
   valid) and Scope Description.
5. Your Client app would have been created and displayed by now.
6. Select the created OAuth client.
7. User this scope `aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL` when you create the grant token.

## Installation

You can install the package via `composer require`:

```bash
composer require optimacloud/zoho-laravel
```
After installing the package you can publish the config file with:

```bash
php artisan vendor:publish --tag="zoho-laravel-config"
```

after that you need to create the OAuth client and get the credentials from Zoho by run the following command:

```bash
php artisan zoho:install
```

You'll need to add the following variables to your .env file. Use the credentials previously obtained registering your
application.

```dotenv
ZOHO_AUTH_FLOW_TYPE=grantToken
ZOHO_CLIENT_ID="Code from Client Secrit Section"
ZOHO_CLIENT_SECRET="Code from Client Secrit Section"
ZOHO_REDIRECT_URI="${APP_URL}/zoho/oauth2callback"
ZOHO_CURRENT_USER_EMAIL=admin@example.com
ZOHO_TOKEN="Code Generated from last step"

# available datacenters (USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter)
ZOHO_DATACENTER=USDataCenter
ZOHO_SANDBOX=true
```

After that you need to run the following command to add token and refresh token to your storage

```bash
php artisan zoho:grant
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="zoho-laravel-config"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="zoho-laravel-migrations"
php artisan migrate
```

### Environments
maybe in some cases you wish to enforce zoho to use one of zoho's environments, so you can go to `AppServiceProvider`
and use `Zoho::useEnvironment()` method

```php
Zoho::useEnvironment(EUDataCenter::DEVELOPER());
```

So that will override config settings.

## Usage

To retrieve all modules from Zoho.

```php
use Optimacloud\Zoho\ZohoManager;

$response = ZohoManager::make(self::TESTING_MODULE);
$modules  = $response->getAllModules();
```

## Models can be used like this:-

Available only starting from **v1.1.0**

add `Zohoable` as extended class like this:-

```php
use Optimacloud\Zoho\Zohoable;
use Optimacloud\Zoho\ZohoManager;

class Invoice extends Zohoable {
    
    // this is your Zoho module API Name
    protected $zoho_module_name = 'Payments';

    public function searchCriteria(){
        // you should return string of criteria that you want to find current record in crm with.
        //EX:
        return ZohoManager::make('Payments')
                            ->where('PaymentID', $this->payment_id)
                            ->andWhere('Order_ID', $this->order_id)
                            ->getCriteria();
    }

    public function zohoMandatoryFields() {
        // you should return array of mandatory fields to create module from this model
        return ['Base_Currency' => $this->currency];
    }
}
```

so now you can use invoice like this

```php
$invoice = \App\Invoice::find(1);

// to check if has zoho id stored on local database or not
$invoice->hasZohoId();

// to return the stored zoho id
$invoice->zohoId();

// that will search on zoho with provided criteria to find the record and associated your model with returned id if exist
// if you provided an `id` that will be used instead of searching on Zoho
$invoice->createOrUpdateZohoId($id = null);

// you can also send current model to zoho
// that wil use `zohoMandatoryFields` method to Serialize model to zohoObject
// Also you can pass additional fields as array to this method
$invoice->createAsZohoable($options = []);
```

**Note:** To use the Invoice like this, you must have the `invoices` table in your database just like you would for any
Laravel model. This allows you to save data to the database and also be able to link it to the `zohos` table and use all
the functions in `Zohoable`. Use the CRUD functions below if you do not intend to use the Zohoable model this way.

## CRUD Can be used like this:-

#### READ

```php
use Optimacloud\Zoho\ZohoManager;

// we can now deal with leads module
$leads = ZohoManager::useModule('Leads');

// OR

$leads = ZohoManager::make('Leads');

// find record by its ID
$lead = $leads->getRecord('3582074000002383003');
```

#### UPDATE

```php
$record = new Record();
$record->setId('3582074000002383003');

// Set value as field
$record->addFieldValue(Leads::FirstName(), 'Updated');
$record->addFieldValue(Leads::LastName(), 'Record');

// Set value as key value
$lead->setKeyValue('Phone', '5555555555552');

// Then call update() method
$response = $leads->update($record);
```

#### CREATE

```php
// create the record into zoho crm then get the created instance data
$response = $leads->create([
    'First_Name' => 'Amr',
    'Last_Name' => 'Emad',
    'Email' => 'test@optimacloud.com',
    'Phone' => '012345678910',
]);

```

#### DELETE

```php
// delete record by its id
$lead = $leads->delete('3582074000002383003');

```

#### SEARCH

##### Word

```php
use Optimacloud\Zoho\ZohoManager;

$records = ZohoManager::useModule('Leads')->searchRecordsByWord('word to be searched');
$first_record = $records[0];
```

##### Phone

```php
use Optimacloud\Zoho\ZohoManager;

$records = ZohoManager::useModule('Leads')->searchRecordsByPhone('12345678910');
$first_record = $records[0];
```

##### Email

```php
use Optimacloud\Zoho\ZohoManager;

$records = ZohoManager::make('Leads')->searchRecordsByEmail('nobody@optimacloud.com');
$first_record = $records[0];
```

##### Criteria

```php
use Optimacloud\Zoho\ZohoManager;

$records = ZohoManager::make('Leads')->searchRecordsByCriteria('(City:equals:NY) and (State:equals:Alden)')->get();
$first_record = $records[0];
```

##### Custom

```php
use Optimacloud\Zoho\ZohoManager;

$records = ZohoManager::make('Leads')
                    ->where('City', 'NY')
                    ->andWhere('State','Alden')
                    ->get();

$first_record = $records[0];
```

## International Versions

If you're using zoho.com, you don't have to change anything.

If you're using zoho.eu, add to `.env`:

```
ZOHO_ACCOUNTS_URL=https://accounts.zoho.eu
ZOHO_API_BASE_URL=www.zohoapis.eu
```

If you're using zoho.com.cn, add to `.env`:

```
ZOHO_ACCOUNTS_URL=https://accounts.zoho.com.cn
ZOHO_API_BASE_URL=www.zohoapis.com.cn
```

## Support

Contact:<br>
[optimacloud.com](https://optimacloud.com)<br>
aemad@asciisd.com<br>
+2-010-1144-1444

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/optimacloud/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [aemaddin](https://github.com/asciisd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
