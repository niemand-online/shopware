<?php

namespace Shopware\Bundle\AccountBundle\Service;

use DateTime;
use Doctrine\DBAL\Connection;
use Shopware\Components\Random;

class OptinService implements OptinServiceInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function generateOptin($optinType, array $data = [], $date = null)
    {
        if (is_null($date)) {
            $date = new DateTime();
        }

        $hash = Random::getAlphanumericString(32);

        $this->insertOptin($optinType, $date, $hash, $data);

        return $hash;
    }
    
    protected function insertOptin($optinType, DateTime $dateTime, $hash, $data)
    {
        $sql = "INSERT INTO `s_core_optin` (`type`, `datum`, `hash`, `data`) VALUES (?, ?, ?, ?)";
        $this->connection->executeQuery($sql, [$optinType, $dateTime->format('Y-m-d H:i:s'), $hash, serialize($data)]);
    }
}
