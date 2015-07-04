<?php namespace JobBrander\Jobs\Client\Providers\Test;

use JobBrander\Jobs\Client\Providers\J2c;
use Mockery as m;

class J2cTest extends \PHPUnit_Framework_TestCase
{
    private $clientClass = 'JobBrander\Jobs\Client\Providers\AbstractProvider';
    private $collectionClass = 'JobBrander\Jobs\Client\Collection';
    private $jobClass = 'JobBrander\Jobs\Client\Job';

    public function setUp()
    {
        $this->params = [
            'partnerId' => '784',
            'partnerPass' => 'qGFjFBrlAEIOAsdx',
        ];
        $this->client = new J2c($this->params);
    }

    public function testItWillUseJsonFormat()
    {
        $format = $this->client->getFormat();

        $this->assertEquals('json', $format);
    }

    public function testItWillUseGetHttpVerb()
    {
        $verb = $this->client->getVerb();

        $this->assertEquals('GET', $verb);
    }

    public function testListingPath()
    {
        $path = $this->client->getListingsPath();

        $this->assertEquals('jobs', $path);
    }

    public function testItWillProvideEmptyParameters()
    {
        $parameters = $this->client->getParameters();

        $this->assertEmpty($parameters);
        $this->assertTrue(is_array($parameters));
    }

    public function testUrlIncludesHighlightWhenProvided()
    {
        $highlight = uniqid();
        $param = 'hl='.$highlight;

        $url = $this->client->setHighlight($highlight)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesEmptyHighlightWhenNotProvided()
    {
        $param = 'hl=';

        $url = $this->client->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesKeywordWhenProvided()
    {
        $keyword = uniqid().' '.uniqid();
        $param = 'q='.urlencode($keyword);

        $url = $this->client->setKeyword($keyword)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesKeywordWhenNotProvided()
    {
        $param = 'q=';

        $url = $this->client->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesLocationWhenCityAndStateProvided()
    {
        $city = uniqid();
        $state = uniqid();
        $param = '&l='.urlencode($city.', '.$state);

        $url = $this->client->setCity($city)->setState($state)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesLocationWhenCityProvided()
    {
        $city = uniqid();
        $param = '&l='.urlencode($city);

        $url = $this->client->setCity($city)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesLocationWhenStateProvided()
    {
        $state = uniqid();
        $param = '&l='.urlencode($state);

        $url = $this->client->setState($state)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesLocationWhenNotProvided()
    {
        $param = '&l=';

        $url = $this->client->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesLimitWhenProvided()
    {
        $limit = uniqid();
        $param = 'limit='.$limit;

        $url = $this->client->setCount($limit)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesLimitWhenNotProvided()
    {
        $param = 'limit=';

        $url = $this->client->setCount(null)->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesIpAddressWhenProvided()
    {
        $ipAddress = uniqid();
        $param = 'ip='.$ipAddress;

        $url = $this->client->setIpAddress($ipAddress)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesIpAddressWhenNotProvided()
    {
        $param = 'ip=';
        $url = $this->client->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesStartWhenProvided()
    {
        $page = uniqid();
        $param = 'start='.$page;

        $url = $this->client->setStart($page)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesStartWhenNotProvided()
    {
        $param = 'start=0';

        $url = $this->client->getUrl();

        $this->assertContains($param, $url);
    }

    public function testItCanCreateJobFromPayload()
    {
        $payload = $this->createJobArray();
        $results = $this->client->createJobObject($payload);

        $this->assertEquals($payload['title'], $results->title);
        $this->assertEquals($payload['description'], $results->description);
        $this->assertEquals($payload['company'], $results->company);
    }

    public function testItCreatesMultipleJobsWhenMultipleLocationsReturned()
    {
        $loc_count = rand(2,5);
        $jobArray = $this->createJobArray($loc_count);

        $array = $this->client->createJobArray($jobArray);

        foreach ($array as $key => $job) {
            $this->assertEquals($jobArray['title'], $array[0]['title']);
            $this->assertEquals($jobArray['city'][$key], $array[$key]['city']);
        }
        $this->assertEquals($loc_count, count($array));
    }

    public function testItCreatesOneJobWhenOneLocationsReturned()
    {
        $loc_count = 1;
        $jobArray = $this->createJobArray($loc_count);

        $array = $this->client->createJobArray($jobArray);

        foreach ($array as $key => $job) {
            $this->assertEquals($jobArray['title'], $array[0]['title']);
            $this->assertEquals($jobArray['city'][$key], $array[$key]['city']);
        }
        $this->assertEquals($loc_count, count($array));
    }

/*
    public function testItCanConnect()
    {
        $provider = $this->getProviderAttributes();

        for ($i = 0; $i < $provider['jobs_count']; $i++) {
            $payload['results'][] = $this->createJobArray();
        }

        $responseBody = json_encode($payload);

        $job = m::mock($this->jobClass);
        $job->shouldReceive('setQuery')->with($provider['keyword'])
            ->times($provider['jobs_count'])->andReturnSelf();
        $job->shouldReceive('setSource')->with($provider['source'])
            ->times($provider['jobs_count'])->andReturnSelf();

        $response = m::mock('GuzzleHttp\Message\Response');
        $response->shouldReceive('getBody')->once()->andReturn($responseBody);

        $http = m::mock('GuzzleHttp\Client');
        $http->shouldReceive(strtolower($this->client->getVerb()))
            ->with($this->client->getUrl(), $this->client->getHttpClientOptions())
            ->once()
            ->andReturn($response);
        $this->client->setClient($http);

        $results = $this->client->getJobs();

        $this->assertInstanceOf($this->collectionClass, $results);
        $this->assertCount($provider['jobs_count'], $results);
    }

    private function getProviderAttributes($attributes = [])
    {
        $defaults = [
            'path' => uniqid(),
            'format' => 'json',
            'keyword' => uniqid(),
            'source' => uniqid(),
            'params' => [uniqid()],
            'jobs_count' => rand(2,10),

        ];
        return array_replace($defaults, $attributes);
    }
    */

    private function createJobArray($loc_count = 3) {
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
            $locations[] = uniqid();
            $i++;
        }
        return $locations;
    }
}
