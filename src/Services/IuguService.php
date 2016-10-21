<?php

namespace Edbizarro\Slacker\Iugu\Services;

use Edbizarro\Slacker\Iugu\Contracts\IuguServiceContract;
use Edbizarro\Slacker\Iugu\Services\Resources\Customer;
use Iugu;

/**
 * Class IuguService.
 */
class IuguService implements IuguServiceContract
{
    /**
     * @var Customer
     */
    protected $iuguCustomer;
    protected $iuguPlan;
    protected $iuguSubscription;

    /**
     * IuguService constructor.
     */
    public function __construct()
    {
        Iugu::setApiKey(config('slash-command-iugu-handler.token'));

        $this->iuguCustomer = new Customer;
    }

    /**
     * @return Customer
     */
    public function customer(): Customer
    {
        return $this->iuguCustomer;
    }
}
