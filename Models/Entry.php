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

namespace OstStatistics\Models;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;
use Shopware\Models\Customer\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Repository")
 * @ORM\Table(name="ost_statistics_entries")
 */
class Entry extends ModelEntity
{
    /**
     * Auto-generated id.
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Date and time of the entry.
     *
     * @var \DateTime
     *
     * @Assert\DateTime()
     *
     * @ORM\Column(name="`date`", type="datetime")
     */
    private $date;

    /**
     * The customer ip.
     *
     * @var string
     *
     * @ORM\Column(name="ip", type="string", nullable=false, length=64)
     */
    private $ip;

    /**
     * ...
     *
     * @var string
     *
     * @ORM\Column(name="sessionId", type="string", nullable=false, length=64)
     */
    private $sessionId;

    /**
     * The frontend controller.
     *
     * @var string
     *
     * @ORM\Column(name="controller", type="string", nullable=false, length=64)
     */
    private $controller;

    /**
     * The unique key - mostly the id parameter.
     *
     * @var integer
     *
     * @ORM\Column(name="`key`", type="integer", nullable=true)
     */
    private $key;

    /**
     * Every other parameter as json string encoded.
     *
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;

    /**
     * ...
     */
    public function __construct()
    {
        // set default values
        $this->date = new \DateTime();
    }

    /**
     * Getter method for the property.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter method for the property.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Setter method for the property.
     *
     * @param \DateTime $date
     *
     * @return void
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Setter method for the property.
     *
     * @param string $ip
     *
     * @return void
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Setter method for the property.
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Setter method for the property.
     *
     * @param string $controller
     *
     * @return void
     */
    public function setController(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Getter method for the property.
     *
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Setter method for the property.
     *
     * @param int $key
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Setter method for the property.
     *
     * @param string $params
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}
