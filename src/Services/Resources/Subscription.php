<?php

namespace Edbizarro\Slacker\Iugu\Services\Resources;

use Iugu_Subscription;

/**
 * Class Subscription.
 */
class Subscription extends IuguResource
{
    /**
     * @var Iugu_Subscription
     */
    protected $iuguSubscription;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->iuguSubscription = new Iugu_Subscription;
    }

    /**
     * @param $key
     * @return \Illuminate\Support\Collection
     */
    public function get($key)
    {
        return $this->formatResponse(
            $this->iuguSubscription->fetch($key)
        );
    }

    /**
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    public function find(array $options = [])
    {
        return $this->formatResponse(
            $this->iuguSubscription->search($options)
        );
    }
}
