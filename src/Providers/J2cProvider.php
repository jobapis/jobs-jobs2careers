<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;

class J2cProvider extends AbstractProvider
{
    /**
     * Takes a job valid for multiple locations and turns it into multiple jobs
     *
     * @param array $item
     *
     * @return array
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
     * @param array $payload Raw job payload from the API
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload = [])
    {
        $job = new Job([
            'title' => $payload['title'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'javascriptFunction' => $payload['onclick'],
            'javascriptAction' => 'onclick',
            'sourceId' => $payload['id'],
            'industry' => $payload['industry0'],
        ]);

        $job->setDatePostedAsString($payload['date'])
            ->setCompany($payload['company']);

        $location = static::parseLocation($payload['city']);

        if (isset($location[0])) {
            $job->setCity($location[0]);
        }
        if (isset($location[1])) {
            $job->setState($location[1]);
        }

        return $job;
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  string
     */
    public function getDefaultResponseFields()
    {
        return [
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
    }

    /**
     * Get listings path
     *
     * @return string
     */
    public function getListingsPath()
    {
        return 'jobs';
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
                $item = static::parseAttributeDefaults($item, $this->getDefaultResponseFields());
                $job = $this->createJobObject($item);
                $job->setQuery($this->query->getKeyword())
                    ->setSource($this->getSource());
                $collection->add($job);
            }
        }, $listings);
        return $collection;
    }
}
