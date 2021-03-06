<?php

namespace Edbizarro\Slacker\Iugu\Services\Resources;

use Iugu_Customer;

/**
 * Class Customer.
 */
class Customer extends IuguResource
{
    /**
     * @var Iugu_Customer
     */
    protected $iuguCustomer;
    protected $iuguSubscription;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->iuguCustomer = new Iugu_Customer;
        $this->iuguSubscription = new Subscription;
    }

    /**
     * @param $key
     * @return \Illuminate\Support\Collection|void
     */
    public function get($key)
    {
        return $this->formatResponse(
            $this->iuguCustomer->fetch($key)
        );
    }

    /**
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    public function find(array $options = [])
    {
        return $this->formatResponse(
            $this->iuguCustomer->search($options)
        );
    }

    /**
     * @return Subscription
     */
    public function subscription(): Subscription
    {
        return $this->iuguSubscription;
    }
}
