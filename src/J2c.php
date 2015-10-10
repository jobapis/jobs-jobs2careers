<?php namespace JobBrander\Jobs\Client\Providers;

use JobBrander\Jobs\Client\Job;
use JobBrander\Jobs\Client\Collection;

class J2c extends AbstractProvider
{
    /**
     * Map of setter methods to query parameters
     *
     * @var array
     */
    protected $queryMap = [
        'setId' => 'id',
        'setPass' => 'pass',
        'setIp' => 'ip',
        'setQ' => 'q',
        'setL' => 'l',
        'setStart' => 'start',
        'setSort' => 'sort',
        'setIndustry' => 'industry',
        'setIndustryEx' => 'industryEx',
        'setFormat' => 'format',
        'setM' => 'm',
        'setLimit' => 'limit',
        'setLink' => 'link',
        'setFull_Desc' => 'full_desc',
        'setJobid' => 'jobid',
        'setJobtype' => 'jobtype',
        'setCount' => 'limit',
        'setKeyword' => 'q',
        'setPartnerId' => 'id',
        'setPartnerPass' => 'pass',
        'setCity' => 'city',
        'setState' => 'state',
    ];

    /**
     * Current api query parameters
     *
     * @var array
     */
    protected $queryParams = [
        'id' => null,
        'pass' => null,
        'ip' => null,
        'q' => null,
        'l' => null,
        'start' => '0',
        'sort' => null,
        'industry' => null,
        'industryEx' => null,
        'format' => 'json',
        'm' => null,
        'limit' => null,
        'link' => null,
        'full_desc' => null,
        'jobid' => null,
        'jobtype' => null,
    ];

    /**
     * Create new J2c jobs client.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);
        array_walk($parameters, [$this, 'updateQuery']);
        // Set default parameters
        if (!isset($this->ip)) {
            $this->updateQuery($this->getIp(), 'ip');
        }
    }

    /**
     * Magic method to handle get and set methods for properties
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (isset($this->queryMap[$method], $parameters[0])) {
            $this->updateQuery($parameters[0], $this->queryMap[$method]);
        }
        return parent::__call($method, $parameters);
    }

    /**
     * Creates an array of jobs out of jobs with multiple locations
     *
     * @param array $item
     *
     * @return array $jobs
     */
    public function createJobArray($item)
    {
        $jobs = [];
        if (isset($item['city']) && count($item['city']) > 1) {
            foreach ($item['city'] as $location) {
                $item['city'] = $location;
                $jobs[] = $item;
            }
        } else {
            $item['city'] = $item['city'][0];
            $jobs[] = $item;
        }
        return $jobs;
    }

    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobBrander\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $defaults = [
            'title',
            'date',
            'onclick',
            'company',
            'city',
            'description',
            'price',
            'id',
            'industry0',
        ];

        $payload = static::parseAttributeDefaults($payload, $defaults);

        $job = new Job([
            'title' => $payload['title'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'javascriptFunction' => $payload['onclick'],
            'javascriptAction' => 'onclick',
            'sourceId' => $payload['id'],
            'industry' => $payload['industry0'],
        ]);

        $location = static::parseLocation($payload['city']);

        $job->setDatePostedAsString($payload['date'])
            ->setCompany($payload['company']);

        if (isset($location[0])) {
            $job->setCity($location[0]);
        }
        if (isset($location[1])) {
            $job->setState($location[1]);
        }

        return $job;
    }

    /**
     * Get data format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'json';
    }

    /**
     * Get IP Address
     *
     * @return  string
     */
    public function getIp()
    {
        if (isset($this->ip)) {
            return $this->ip;
        } else {
            return getHostByName(getHostName());
        }
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'jobs';
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        return http_build_query($this->queryParams);
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        $query_string = $this->getQueryString();
        return 'http://api.jobs2careers.com/api/search.php?'.$query_string;
    }

    /**
     * Get http verb
     *
     * @return  string
     */
    public function getVerb()
    {
        return 'GET';
    }

    /**
     * Create and get collection of jobs from given listings
     *
     * @param  array $listings
     *
     * @return Collection
     */
    protected function getJobsCollectionFromListings(array $listings = array())
    {
        $collection = new Collection;
        array_map(function ($item) use ($collection) {
            $jobs = $this->createJobArray($item);
            foreach ($jobs as $item) {
                $job = $this->createJobObject($item);
                $job->setQuery($this->keyword)
                    ->setSource($this->getSource());
                $collection->add($job);
            }
        }, $listings);
        return $collection;
    }

    /**
     * Attempts to update current query parameters.
     *
     * @param  string  $value
     * @param  string  $key
     *
     * @return Careerbuilder
     */
    protected function updateQuery($value, $key)
    {
        if (array_key_exists($key, $this->queryParams)) {
            $this->queryParams[$key] = $value;
        }
        return $this;
    }
}
