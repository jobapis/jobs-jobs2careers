# [![JobApis.com](https://i.imgur.com/9VOAkrZ.png)](https://www.jobapis.com) Jobs2Careers Job Board API Client

[![Twitter URL](https://img.shields.io/twitter/url/https/twitter.com/jobapis.svg?style=social&label=Follow%20%40jobapis)](https://twitter.com/jobapis)
[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://github.com/jobapis/jobs-jobs2careers/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-jobs2careers/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-jobs2careers)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobs2careers/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobs2careers)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-jobs2careers)


## About

This package makes it easy to connect your PHP project to the [Jobs2Careers API](http://api.jobs2careers.com/api/spec.pdf). It uses the [Jobs Common project](https://github.com/jobapis/jobs-common) to standardize responses using Schema.org's [JobPosting specification](https://schema.org/JobPosting).

### Example

Getting jobs from the API just takes a couple lines of code:

```php
$query = new J2cQuery([
    'id' => YOUR_PUBLISHER_ID,
    'pass' => YOUR_PUBLISHER_PASSWORD,
    'q' => YOUR_KEYWORD_SEARCH,
    'l' => YOUR_LOCATION,
]);
$client = new J2cProvider($query);
$jobs = $client->getJobs();
```

See [Usage](#usage) section below for more detailed examples.

### Mission

[JobApis](https://www.jobapis.com) makes job board and company data more accessible through open source software. To learn more, visit [JobApis.com](https://www.jobapis.com), or contact us at [admin@jobapis.com](mailto:admin@jobapis.com).


## Requirements
- [PHP 5.5+](http://www.php.net/)
- [Composer](https://getcomposer.org/)


## Installation

To install, use composer:

```
composer require jobapis/jobs-jobs2careers
```

## Usage

Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\J2cQuery([
    'id' => YOUR_PUBLISHER_ID,
    'pass' => YOUR_PUBLISHER_PASSWORD,
]);
```

Or via the "set" method. All of the parameters documented in the documentation can be added.

```php
// Add parameters via the set() method
$query->set('q', 'engineering');
```

You can chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('l', 'Chicago, IL')
    ->set('start', 10)
    ->set('limit', 20);
```
 
Then inject the query object into the provider.

```php
// Instantiating the Provider with a query object
$client = new JobApis\Jobs\Client\Providers\J2cProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

The `getJobs()` method will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects based on Schema.org's [JobPosting](https://schema.org/JobPosting) specification.

## Testing

1. Clone this repository from Github.
2. Install the dependencies with Composer: `$ composer install`.
3. Run the test suite: `$ ./vendor/bin/phpunit`.
4. (Optional) To run all tests including actual API calls: `$ ID=<YOUR PUBLISHER ID> PASS=<YOUR PUBLISHER PASSWORD> ./vendor/bin/phpunit`


## Contributing

Contributions are welcomed and encouraged! Please see [JobApis' contribution guidelines](https://www.jobapis.com/contributing/) for details, or create an issue in Github if you have any questions.

## Legal

### Disclaimer

This package is not affiliated with or supported by :provider_name and we are not responsible for any use or misuse of this software.

### License

This package uses the Apache 2.0 license. Please see the [License File](https://www.jobapis.com/license/) for more information.

### Copyright

Copyright 2017, Karl Hughes <khughes.me@gmail.com>.
