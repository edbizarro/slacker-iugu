<?php

namespace Edbizarro\Slacker\Iugu\Contracts;

use APIResource;
use Illuminate\Support\Collection;

/**
 * Interface IuguResourcesContract.
 */
interface IuguResourcesContract
{
    /**
     * @param APIResource|array $apiResponse
     * @return Collection
     */
    public function formatResponse($apiResponse): Collection;
}
