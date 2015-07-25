<?php namespace JobBrander\Jobs\Client\Providers;

use JobBrander\Jobs\Client\Job;
use JobBrander\Jobs\Client\Collection;

class J2c extends AbstractProvider
{
    /**
     * Partner Id
     *
     * @var string
     */
    protected $partnerId;

    /**
     * Partner Password
     *
     * @var string
     */
    protected $partnerPass;

    /**
     * Client IP Address
     *
     * @var string
     */
    protected $ipAddress;

    /**
     * Highlight
     *
     * @var string
     */
    protected $highlight;

    /**
     * Query params
     *
     * @var array
     */
    protected $queryParams = [];

    /**
     * Add query params, if valid
     *
     * @param string $value
     * @param string $key
     *
     * @return  void
     */
    private function addToQueryStringIfValid($value, $key)
    {
        $computed_value = $this->$value();
        if (!is_null($computed_value)) {
            $this->queryParams[$key] = $computed_value;
        }
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

        $location = $this->parseLocation($payload['city']);

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
    public function getIpAddress()
    {
        if (isset($this->ipAddress)) {
            return $this->ipAddress;
        } else {
            return getHostByName(getHostName());
        }
    }

    /**
     * Get Start number
     *
     * @return  string
     */
    public function getStart()
    {
        if (isset($this->start)) {
            return $this->start;
        } else {
            return '0';
        }
    }

    /**
     * Get Highlight wrapper
     *
     * @return  string
     */
    public function getHighlight()
    {
        if (isset($this->highlight)) {
            return $this->highlight;
        } else {
            return '';
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
     * Get combined location
     *
     * @return string
     */
    public function getLocation()
    {
        $location = ($this->city ? $this->city.', ' : null).($this->state ?: null);

        if ($location) {
            return $location;
        }

        return null;
    }

    /**
     * Get parameters
     *
     * @return  array
     */
    public function getParameters()
    {
        return [];
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        $query_params = [
            'id' => 'getPartnerId',
            'pass' => 'getPartnerPass',
            'ip' => 'getIpAddress',
            'format' => 'getFormat',
            'q' => 'getKeyword',
            'l' => 'getLocation',
            'start' => 'getStart',
            'limit' => 'getCount',
            'hl' => 'getHighlight',
        ];

        array_walk($query_params, [$this, 'addToQueryStringIfValid']);

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
     * Parse city and state from string given by API
     *
     * @return array
     */
    public function parseLocation($location)
    {
        return explode(', ', $location);
    }
}
