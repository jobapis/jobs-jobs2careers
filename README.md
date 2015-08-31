# Jobs2Careers Jobs Client

[![Latest Version](https://img.shields.io/github/release/JobBrander/jobs-jobs2careers.svg?style=flat-square)](https://github.com/JobBrander/jobs-jobs2careers/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/JobBrander/jobs-jobs2careers/master.svg?style=flat-square&1)](https://travis-ci.org/JobBrander/jobs-jobs2careers)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/JobBrander/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-jobs2careers/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/JobBrander/jobs-jobs2careers.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-jobs2careers)
[![Total Downloads](https://img.shields.io/packagist/dt/jobbrander/jobs-jobs2careers.svg?style=flat-square)](https://packagist.org/packages/jobbrander/jobs-jobs2careers)

This package provides [Jobs2Careers API](http://api.jobs2careers.com/api/spec.pdf)
support for the JobBrander's [Jobs Client](https://github.com/JobBrander/jobs-common).

## Installation

To install, use composer:

```
composer require jobbrander/jobs-jobs2careers
```

## Usage

Usage is the same as Job Branders's Jobs Client, using `\JobBrander\Jobs\Client\Provider\J2c` as the provider.

```php
$client = new JobBrander\Jobs\Client\Provider\J2c([
    'id' => 'Jobs2Careers Partner ID',
    'password' => 'Jobs2Careers Password'
]);

// Search for 200 job listings for 'project manager' in Chicago, IL
$jobs = $client->setKeyword('project manager') // Query string (keyword) to search for
    ->setCity('Chicago')    // Combined with state to create 'location' parameter in API
    ->setState('IL')
    ->setCount(200)         // Max number of results (not to exceed 200)
    ->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/JobBrander/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/JobBrander/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobbrander/jobs-jobs2careers/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobbrander/jobs-jobs2careers/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobbrander/jobs-jobs2careers/blob/master/LICENSE) for more information.
