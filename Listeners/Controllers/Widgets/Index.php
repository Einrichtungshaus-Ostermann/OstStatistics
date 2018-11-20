<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Statistics
 *
 * @package   OstStatistics
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstStatistics\Listeners\Controllers\Widgets;

use Enlight_Event_EventArgs as EventArgs;
use OstStatistics\Services\StatisticsServiceInterface;

class Index
{
    /**
     * ...
     *
     * @var StatisticsServiceInterface
     */
    protected $statisticsService;

    /**
     * ...
     *
     * @param StatisticsServiceInterface $statisticsService
     */
    public function __construct(StatisticsServiceInterface $statisticsService)
    {
        // set params
        $this->statisticsService = $statisticsService;
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onRefreshStatistic(EventArgs $arguments)
    {
        // create an entry for this "click"
        $this->statisticsService->create();
    }
}
