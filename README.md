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
    'pass' => 'Jobs2Careers Password'
]);

// Search for 200 job listings for 'project manager' in Chicago, IL
$jobs = $client
    // API Parameters from [Official Jobs2Careers documentation](http://api.jobs2careers.com/api/spec.pdf)
    ->setId()    // (Required) Your unique Publisher ID (as shown in the Feed Manager)
    ->setPass()    // (Required) Your unique Publisher Password (as shown in your Feed Manager)
    ->setIp()    // (Required) Preferably the END USER's IP (unless email)
    ->setQ()    // (Either q or l or both should be present) URL encoded query string (keyword)
    ->setL()    // (Either q or l or both should be present) location (city, state, zip, etc)
    ->setStart()    // (Optional) Start offset
    ->setSort()    // (Optional) Either "d" by date or "r" by relevance, defaults to "r"
    ->setIndustry()    // (Optional) An industry string or ID (see API docs) to include
    ->setIndustryex()    // (Optional) An industry string or ID (see API docs) to exclude
    ->setFormat()    // (Optional) API output format (should not be used)
    ->setM()    // (Optional) Mobile optimized jobs only if "1"
    ->setLimit()    // (Optional) Max number of results (not to exceed 200). Defaults to 10
    ->setLink()    // (Optional) Bypass JS to expose the direct link to the listing
    ->setFull_Desc()    // (Optional) Shows full job description if "1"
    ->setJobid()    // (Optional) Return only a specific active job ID
    ->setJobtype()    // (Optional) Return only full time (1), part time (2), or gigs (4). Can have 1 or more values
    // Extra parameters, not supported by official API
    ->setCount(200)         // Alias for setLimit()
    ->setKeyword('project manager') // Alias for setQ()
    ->setPartnerId()        // Alias for setId()
    ->setPartnerPass()      // Alias for setPass()
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
