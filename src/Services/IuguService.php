<?php

namespace Edbizarro\Slacker\Iugu\Services;

use Edbizarro\Slacker\Iugu\Contracts\IuguServiceContract;
use Edbizarro\Slacker\Iugu\Services\Resources\Customer;
use Edbizarro\Slacker\Iugu\Services\Resources\Subscription;
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

    /**
     * @var Subscription
     */
    protected $iuguSubscription;

    /**
     * IuguService constructor.
     */
    public function __construct()
    {
        Iugu::setApiKey(config('slash-command-iugu-handler.token'));

        $this->iuguCustomer = new Customer;
        $this->iuguSubscription = new Subscription;
    }

    /**
     * @return Customer
     */
    public function customer(): Customer
    {
        return $this->iuguCustomer;
    }

    /**
     * @return Subscription
     */
    public function subscription(): Subscription
    {
        return $this->iuguSubscription;
    }
}
