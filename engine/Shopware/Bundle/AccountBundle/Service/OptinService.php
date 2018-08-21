<?php

namespace Shopware\Bundle\AccountBundle\Service;

use DateTime;
use Doctrine\DBAL\Connection;
use Exception;
use InvalidArgumentException;
use Shopware\Bundle\AccountBundle\Exception\OptinCreateException;
use Shopware\Bundle\AccountBundle\Struct\Optin;
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
    public function create(Optin $optin)
    {
        try {
            return $this->createUnsafe($optin);
        } catch (Exception $exception) {
            throw new OptinCreateException($exception);
        }
    }

    /**
     * @param Optin $optin
     * @return Optin
     * @throws InvalidArgumentException
     */
    protected function createUnsafe(Optin $optin)
    {
        if (!is_null($optin->getId())) {
            throw new InvalidArgumentException('$optin has id and may be already stored');
        }

        if (empty($optin->getType())) {
            throw new InvalidArgumentException('$optin has no type');
        }

        $payload = clone $optin;

        if (is_null($payload->getDate())) {
            $payload->setDate(new DateTime());
        }

        if (empty($payload->getHash())) {
            $payload->setHash(Random::getAlphanumericString(32));
        }

        $id = $this->insertOptin($payload->getType(), $payload->getDate(), $payload->getHash(), $payload->getData());

        $payload->setId($id);
        return $payload;
    }

    /**
     * @param string $optinType
     * @param DateTime $dateTime
     * @param string $hash
     * @param string $data
     * @return int|null
     */
    protected function insertOptin($optinType, DateTime $dateTime, $hash, $data)
    {
        $sql = "INSERT INTO `s_core_optin` (`type`, `datum`, `hash`, `data`) VALUES (?, ?, ?, ?)";
        if ($this->connection->insert($sql, [$optinType, $dateTime->format('Y-m-d H:i:s'), $hash, $data])) {
            return (int) $this->connection->lastInsertId();
        }

        return null;
    }
}
