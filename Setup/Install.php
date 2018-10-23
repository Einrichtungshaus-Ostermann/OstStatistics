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

namespace OstStatistics\Setup;

use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use OstStatistics\Models;

class Install
{
    /**
     * Main bootstrap object.
     *
     * @var Plugin
     */
    protected $plugin;



    /**
     * ...
     *
     * @var InstallContext
     */
    protected $context;



    /**
     * ...
     *
     * @var ModelManager
     */
    protected $modelManager;



    /**
     * ...
     *
     * @var CrudService
     */
    protected $crudService;


    /**
     * ...
     *
     * @var array
     */

    protected $models = array(
        Models\Entry::class
    );



    /**
     * ...
     *
     * @param Plugin         $plugin
     * @param InstallContext $context
     * @param ModelManager   $modelManager
     * @param CrudService    $crudService
     */
    public function __construct(Plugin $plugin, InstallContext $context, ModelManager $modelManager, CrudService $crudService)
    {
        // set params
        $this->plugin = $plugin;
        $this->context = $context;
        $this->modelManager = $modelManager;
        $this->crudService = $crudService;
    }



    /**
     * ...
     *
     * @throws \Exception
     */
    public function install()
    {
        // ...
        $this->installModels();
    }



    /**
     * ...
     *
     * @return void
     */

    private function installModels()
    {
        // get entity manager
        $em = $this->modelManager;

        // get our schema tool
        $tool = new SchemaTool( $em );

        // ...
        $classes = array_map(
            function( $model ) use ( $em ) {
                return $em->getClassMetadata( $model );
            },
            $this->models
        );

        // remove them
        $tool->createSchema( $classes );
    }



}
