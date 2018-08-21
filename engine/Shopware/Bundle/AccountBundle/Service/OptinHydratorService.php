<?php

namespace Shopware\Bundle\AccountBundle\Service;

use DateTime;
use Shopware\Bundle\AccountBundle\Struct\Optin;

class OptinHydratorService implements OptinHydratorServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate(array $data)
    {
        $result = new Optin();

        if (array_key_exists('id', $data) && is_numeric($data['id'])) {
            $result->setId(intval($data['id']));
        }

        if (array_key_exists('hash', $data) && is_string($data['hash'])) {
            $result->setHash($data['hash']);
        }

        if (array_key_exists('type', $data) && is_string($data['type'])) {
            $result->setType($data['type']);
        }

        if (array_key_exists('data', $data)) {
            if (is_array($data['data'])) {
                $result->setData(serialize($data['data']));
            } else if (is_scalar($data['data'])) {
                $result->setData((string) $data['data']);
            }
        }

        if (array_key_exists('date', $data) && $data['date'] instanceof DateTime) {
            $result->setDate($data['date']);
        }

        if (array_key_exists('datum', $data) && $data['datum'] instanceof DateTime) {
            $result->setDate($data['datum']);
        }

        return $result;
    }
}
