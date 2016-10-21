<?php

namespace Edbizarro\Slacker\Iugu\Contracts;

use APIResource;
use Illuminate\Support\Collection;
use Iugu_SearchResult;

/**
 * Interface IuguResourcesContract.
 */
interface IuguResourcesContract
{
    /**
     * @param APIResource $apiResponse
     * @return Collection
     */
    public function formatResponse(Iugu_SearchResult $apiResponse): Collection;
}
