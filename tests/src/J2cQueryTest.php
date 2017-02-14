<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\J2cQuery;
use Mockery as m;

class J2cQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Set up server variables for testing
        $_SERVER['REMOTE_ADDR'] = uniqid();

        $this->query = new J2cQuery();
    }

    public function testItAddsDefaultAttributes()
    {
        $this->assertEquals($_SERVER['REMOTE_ADDR'], $this->query->get('ip'));
        $this->assertEquals('json', $this->query->get('format'));
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://api.jobs2careers.com/api/search.php',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('q', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('id', uniqid());
        $this->query->set('pass', uniqid());
        $this->query->set('q', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $url = $this->query->getUrl();
        $this->assertContains('ip=', $url);
        $this->assertContains('format=', $url);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenSettingInvalidAttribute()
    {
        $this->query->set(uniqid(), uniqid());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenGettingInvalidAttribute()
    {
        $this->query->get(uniqid());
    }

    public function testItSetsAndGetsValidAttributes()
    {
        $attributes = [
            'q' => uniqid(),
            'l' => uniqid(),
            'id' => uniqid(),
            'pass' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
