<?php namespace JobApis\Jobs\Client\Queries;

class J2cQuery extends AbstractQuery
{
    /**
     * id
     *
     * Publisher ID
     *
     * @var string
     */
    protected $id;

    /**
     * pass
     *
     * Publisher Password
     *
     * @var string
     */
    protected $pass;

    /**
     * ip
     *
     * IP Address
     *
     * @var string
     */
    protected $ip;

    /**
     * q
     *
     * Keyword/search query
     *
     * @var string
     */
    protected $q;

    /**
     * l
     *
     * Location
     *
     * @var string
     */
    protected $l;

    /**
     * start
     *
     * Starting result #
     *
     * @var integer
     */
    protected $start;

    /**
     * limit
     *
     * Max # of results to return
     *
     * @var integer
     */
    protected $limit;

    /**
     * sort
     *
     * Sort by:
     *  d - by date
     *  r - by relevance
     *
     * @var string
     */
    protected $sort;

    /**
     * industry
     *
     * Industry code to search by
     *
     * @var string
     */
    protected $industry;

    /**
     * industryEx
     *
     * Industry code to exclude
     *
     * @var string
     */
    protected $industryEx;

    /**
     * format
     *
     * Must be `json`
     *
     * @var string
     */
    protected $format;

    /**
     * m
     *
     * Mobile-optimized jobs only
     *
     * @var boolean
     */
    protected $m;

    /**
     * link
     *
     * Bypass the JavaScript function to expose a direct link. Default = 0
     *
     * @var boolean
     */
    protected $link;

    /**
     * full_desc
     *
     * Full job description
     *
     * @var boolean
     */
    protected $full_desc;

    /**
     * jobid
     *
     * Job ID to search for
     *
     * @var string
     */
    protected $jobid;

    /**
     * jobtype
     *
     * Job type to search for
     *
     * @var string
     */
    protected $jobtype;

    /**
     * d
     *
     * Radius to search
     *
     * @var integer
     */
    protected $d;

    /**
     * useragent
     *
     * Pass in the user agent to prioritize mobile jobs
     *
     * @var string
     */
    protected $useragent;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://api.jobs2careers.com/api/search.php';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->q;
    }

    /**
     * Default parameters
     *
     * @var array
     */
    protected function defaultAttributes()
    {
        return [
            'format' => 'json',
            'ip' => $this->userIp(),
        ];
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'id',
            'pass',
            'ip',
            'q',
        ];
    }

    /**
     * Return the IP address from server
     *
     * @return  string
     */
    protected function userIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : getHostByName(getHostName());
    }
}
