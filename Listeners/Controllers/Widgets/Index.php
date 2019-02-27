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
        // get the referer
        $referer = $_SERVER['HTTP_REFERER'];

        // parse the url
        $params = parse_url($referer);

        // do we have query parameters? force a default value
        if (!isset( $params['query'])) $params['query'] = "";

        // get the query parameters
        $query = array();
        parse_str($params['query'], $query);

        // set it for specific url params
        // although we never use the params array anymore...
        $params['query'] = $query;

        // no statistics ever with long and short syntax
        if ((isset($query['noStatistics'])) or (isset($query['nst'])))
            // dont count anything
            return;

        // create an entry for this "click"
        $this->statisticsService->create($query);
    }
}
