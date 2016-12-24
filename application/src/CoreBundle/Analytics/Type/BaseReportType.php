<?php

namespace CoreBundle\Analytics\Type;

class BaseReportType
{
    /**
     * @var integer
    */
    private $sessions;

    /**
     * @var integer
    */
    private $users;

    /**
     * @var integer
    */
    private $pageViews;

    /**
     * @var float
    */
    private $avgSessionDuration;

    /**
     * @var float
     */
    private $bounceRate;

    /**
     * @var float
    */
    private $exitRate;

    /**
     * @param int $sessions
     * @return $this
     */
    public function setSessions($sessions)
    {
        $this->sessions = $sessions;

        return $this;
    }

    /**
     * @return int
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * @param int $users
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param int $pageViews
     * @return $this
     */
    public function setPageViews($pageViews)
    {
        $this->pageViews = $pageViews;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageViews()
    {
        return $this->pageViews;
    }

    /**
     * @param float $avgSessionDuration
     * @return $this
     */
    public function setAvgSessionDuration($avgSessionDuration)
    {
        $this->avgSessionDuration = $avgSessionDuration;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgSessionDuration()
    {
        return $this->avgSessionDuration;
    }

    /**
     * @param float $bounceRate
     * @return $this
     */
    public function setBounceRate(float $bounceRate)
    {
        $this->bounceRate = $bounceRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getBounceRate()
    {
        return $this->bounceRate;
    }

    /**
     * @param float $exitRate
     * @return $this
     */
    public function setExitRate(float $exitRate)
    {
        $this->exitRate = $exitRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getExitRate()
    {
        return $this->exitRate;
    }
}