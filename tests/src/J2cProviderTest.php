<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\J2cProvider;
use JobApis\Jobs\Client\Queries\J2cQuery;
use Mockery as m;

class J2cProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_SERVER['REMOTE_ADDR'] = uniqid();

        $this->query = m::mock(J2cQuery::class);

        $this->client = new J2cProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
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
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('jobs', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();
        $payload['city'] = uniqid().', '.uniqid();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['title'], $results->getTitle());
        $this->assertEquals($payload['title'], $results->getName());
        $this->assertEquals($payload['description'], $results->getDescription());
        $this->assertEquals($payload['company'], $results->getCompanyName());
        $this->assertEquals($payload['onclick'], $results->getJavascriptFunction());
        $this->assertEquals($payload['id'], $results->getSourceId());
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $url = 'http://api.jobs2careers.com/api/search.php';

        $options = [
            'q' => uniqid(),
            'l' => uniqid(),
            'id' => uniqid(),
            'pass' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new J2cQuery($options);

        $client = new J2cProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobObjects = [
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
        ];

        $jobs = json_encode((object) [
            'jobs' => $jobObjects
        ]);

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(count($jobObjects), $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('ID')) {
            $this->markTestSkipped('ID not set. Real API call will not be made.');
        }
        if (!getenv('PASS')) {
            $this->markTestSkipped('PASS not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new J2cQuery([
            'q' => $keyword,
            'id' => getenv('ID'),
            'pass' => getenv('PASS'),
        ]);

        $client = new J2cProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray($loc_count = 1) {
        return [
            'title' => uniqid(),
            'date' => '2015-07-02T00:08:59Z',
            'onclick' => uniqid(),
            'company' => uniqid(),
            'city' => $this->createLocationsArray($loc_count),
            'description' => uniqid(),
            'price' => uniqid(),
            'id' => uniqid(),
            'industry0' => uniqid(),
        ];
    }

    private function createLocationsArray($loc_count = 3) {
        $locations = [];
        $i = 0;
        while ($i < $loc_count) {
            $locations[] = uniqid().', '.uniqid();
            $i++;
        }
        return $locations;
    }
}
