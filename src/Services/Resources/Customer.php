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

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->iuguCustomer = new Iugu_Customer;
    }

    /**
     * @param $key
     * @return \Iugu_SearchResult|null
     */
    public function get($key)
    {
        return $this->iuguCustomer->fetch($key);
    }

    /**
     * @param array $options
     * @return array|\Iugu_SearchResult|null
     */
    public function find(array $options = [])
    {
        return $this->formatResponse(
            $this->iuguCustomer->search($options)
        );
    }
}
