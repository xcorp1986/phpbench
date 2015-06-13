<?php

/*
 * This file is part of the PHP Bench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Tests\Functional\ReportGenerator;

abstract class BaseTabularReportGeneratorCase extends BaseReportGeneratorCase
{
    /**
     * It should run without any options.
     */
    public function testNoOptions()
    {
        $this->executeReport($this->getResults(), array());
    }

    /**
     * It should change the precision.
     */
    public function testWithPrecision()
    {
        $this->executeReport($this->getResults(), array(
            'precision' => 2,
        ));
    }

    /**
     * It should aggregate by run.
     */
    public function testWithAggregateIterations()
    {
        $this->executeReport($this->getResults(), array(
            'aggregate' => 'run',
        ));
    }

    /**
     * It should aggregate subject.
     */
    public function testWithAggregateSubject()
    {
        $this->executeReport($this->getResults(), array(
            'aggregate' => 'subject',
        ));
    }

    /**
     * It should allow the variance column for aggregations
     */
    public function testWithAggregateSubjectWithVariance()
    {
        $this->executeReport($this->getResults(), array(
            'aggregate' => 'subject',
            'cols' => array('variance_time'),
        ));
    }

    /**
     * It should allow revolutions.
     */
    public function testWithRevolutions()
    {
        $this->executeReport($this->getResults(), array(
            'cols' => array('rps'),
        ));
    }

    /**
     * It should allow memory.
     */
    public function testWithMemory()
    {
        $this->executeReport($this->getResults(), array(
            'cols' => array('memory', 'memory_diff'),
        ));
    }

    /**
     * It should display time as a fraction of a second.
     */
    public function testWithTimeFraction()
    {
        $this->executeReport($this->getResults(), array(
            'time_format' => 'fraction',
        ));
    }

    /**
     * It should display time as the number of microseconds.
     */
    public function testWithTimeInteger()
    {
        $this->executeReport($this->getResults(), array(
            'time_format' => 'integer',
        ));
    }

    /**
     * It should display deviations.
     */
    public function testDeviation()
    {
        $this->executeReport($this->getResults(), array(
            'cols' => array('deviation_min'),
        ));
    }

    /**
     * It should allow groups to be specified.
     */
    public function testGroups()
    {
        $this->executeReport($this->getResults(), array(
            'groups' => array('foo'),
        ));
    }

    /**
     * It should throw an exception if an invalid aggregation value is given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The option "aggregate"
     */
    public function testInvalidAggregation()
    {
        $this->executeReport($this->getResults(), array(
            'aggregate' => 'hahahaha',
        ));
    }

    /**
     * It should throw an exception if an invalid cols are given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Specified column(s) "foooo"
     */
    public function testInvalidCols()
    {
        $this->executeReport($this->getResults(), array(
            'cols' => array('foooo'),
        ));
    }
}
