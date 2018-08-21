<?php

namespace Shopware\Bundle\AccountBundle\Exception;

use Exception;

class OptinCreateException extends Exception
{
    public function __construct(Exception $previous)
    {
        parent::__construct('An error occured during creation or persist the optin.', 0, $previous);
    }
}
