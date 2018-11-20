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
    public function create()
    {
        // create an entry
        $entry = new Entry();

        // set it up
        $entry->setDate(new \DateTime());
        $entry->setIp($this->request->getClientIp());
        $entry->setSessionId($this->session->offsetGet('sessionId'));
        $entry->setController($this->request->getParam('requestController'));
        $entry->setKey($this->getKey());
        $entry->setParams(null);

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
        }

        // no key context for this controller
        return null;
    }
}
