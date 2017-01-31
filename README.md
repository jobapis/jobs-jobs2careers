# Jobs2Careers Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://github.com/jobapis/jobs-jobs2careers/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-jobs2careers/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-jobs2careers)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobs2careers/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobs2careers)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-jobs2careers.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-jobs2careers)

This package provides [Jobs2Careers API](http://api.jobs2careers.com/api/spec.pdf)
support for our [Jobs Common project](https://github.com/jobapis/jobs-common).

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
// Instantiating an IndeedProvider with a query object
$client = new JobApis\Jobs\Client\Provider\J2cProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

This will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

## Testing

To run all tests except for actual API calls
``` bash
$ ./vendor/bin/phpunit
```

To run all tests including actual API calls
``` bash
$ ID=<YOUR PUBLISHER ID> PASS=<YOUR PUBLISHER PASSWORD> ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobapis/jobs-jobs2careers/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-jobs2careers/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-jobs2careers/blob/master/LICENSE) for more information.
