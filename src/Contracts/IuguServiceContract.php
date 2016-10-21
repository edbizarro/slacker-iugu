<?php

namespace Edbizarro\Slacker\Iugu\Contracts;

use Edbizarro\Slacker\Iugu\Services\Resources\Customer;

/**
 * Interface IuguServiceContract.
 */
interface IuguServiceContract
{
    /**
     * @return Customer
     */
    public function customer(): Customer;
}
