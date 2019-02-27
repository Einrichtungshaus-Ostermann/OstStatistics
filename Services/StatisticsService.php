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

namespace OstStatistics\Services;

use Enlight_Components_Session_Namespace as Session;
use Enlight_Controller_Front as Front;
use Enlight_Controller_Request_Request as Request;
use OstStatistics\Models\Entry;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Components\Model\ModelManager;

class StatisticsService implements StatisticsServiceInterface
{
    /**
     * ...
     *
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * ...
     *
     * @var Session
     */
    protected $session;

    /**
     * ...
     *
     * @var ContextServiceInterface
     */
    protected $contextService;

    /**
     * ...
     *
     * @var Request
     */
    protected $request;

    /**
     * ...
     *
     * @param ModelManager            $modelManager
     * @param Front                   $front
     * @param Session                 $session
     * @param ContextServiceInterface $contextService
     */
    public function __construct(ModelManager $modelManager, Session $session, ContextServiceInterface $contextService, Front $front)
    {
        // set params
        $this->modelManager = $modelManager;
        $this->session = $session;
        $this->contextService = $contextService;
        $this->request = $front->Request();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $refererParams)
    {
        // create an entry
        $entry = new Entry();

        // get the url parameters
        $params = $this->getParams($refererParams);

        // set it up
        $entry->setDate(new \DateTime());
        $entry->setIp($this->request->getClientIp());
        $entry->setSessionId($this->session->offsetGet('sessionId'));
        $entry->setController($this->request->getParam('requestController'));
        $entry->setKey($this->getKey());
        $entry->setParams((empty($params) ? null : json_encode($params)));

        // save it
        $this->modelManager->persist($entry);
        $this->modelManager->flush($entry);
    }

    /**
     * ...
     *
     * @return int|null
     */
    private function getKey()
    {
        // get the key by requested controller
        switch ($this->request->getParam('requestController')) {
            // article details
            case 'detail':
                // return available article id
                return (int) $this->request->getParam('articleId');

            // category listing
            case 'listing':
                // find the shopware path by seo url
                $query = '
                    SELECT org_path
                    FROM s_core_rewrite_urls
                    WHERE path LIKE :path
                        AND main = 1
                        AND subshopID = :shopId
                ';
                $path = Shopware()->Db()->fetchOne($query, [
                    'path'   => ltrim((string) $this->request->getParam('requestPage'), '/'),
                    'shopId' => $this->contextService->getShopContext()->getShop()->getParentId()
                ]);

                // parse the string to get the category id
                parse_str($path, $arr);

                // return the category id
                return (int) $arr['sCategory'];

            // emotion start pages
            case 'campaign':
                // find the shopware path by seo url
                $query = '
                    SELECT org_path
                    FROM s_core_rewrite_urls
                    WHERE path LIKE :path
                        AND main = 1
                        AND subshopID = :shopId
                ';
                $path = Shopware()->Db()->fetchOne($query, [
                    'path'   => ltrim((string) $this->request->getParam('requestPage'), '/'),
                    'shopId' => $this->contextService->getShopContext()->getShop()->getParentId()
                ]);

                // parse the string to get the category id
                parse_str($path, $arr);

                // return the category id
                return (int) $arr['emotionId'];
        }

        // no key context for this controller
        return null;
    }

    /**
     * ...
     *
     * @param array $refererParams
     *
     * @return array
     */
    private function getParams($refererParams)
    {
        // ...
        $params = array();

        // ...
        switch ($this->request->getParam('requestController')) {
            // article details
            case 'detail':
                $params['action'] = "index";
                break;

            // category listing
            case 'listing':
                $params['action'] = "index";
                break;

            // default known controllers
            case "checkout":
            case "address":
            case "account":
            case "register":
                $params['action'] = (ltrim($this->request->getParam('requestPage'), '/') == ltrim($this->request->getParam('requestController'), '/'))
                    ? 'index'
                    : trim(str_replace('/' . ltrim($this->request->getParam('requestController'), '/') . '/', '', $this->request->getParam('requestPage')), '/');
                break;

            // search
            case "search":
                // default action paramter
                $params['action'] = "index";

                // the search string
                $params['search'] = $refererParams['sSearch'];

                // and done
                break;

            // consultant article search
            case "OstArticleSearch":
                // default action paramter
                $params['action'] = (ltrim($this->request->getParam('requestPage'), '/') == ltrim($this->request->getParam('requestController'), '/'))
                    ? 'index'
                    : trim(str_replace('/' . ltrim($this->request->getParam('requestController'), '/') . '/', '', $this->request->getParam('requestPage')), '/');

                // do we have a specific search? or are we in the search form
                if (isset($refererParams['ostas_search']))
                    $params['ostas_search'] = $refererParams['ostas_search'];

                // stop
                break;
        }

        // ...
        return $params;
    }
}
