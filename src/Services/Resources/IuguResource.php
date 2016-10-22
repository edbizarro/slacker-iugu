<?php

namespace Edbizarro\Slacker\Iugu\Services\Resources;

use Edbizarro\Slacker\Iugu\Contracts\IuguResourcesContract;
use Illuminate\Support\Collection;
use Iugu_SearchResult;

/**
 * Class Customer.
 */
class IuguResource implements IuguResourcesContract
{
    /**
     * @param Iugu_SearchResult|array $apiResponse
     * @return Collection
     */
    public function formatResponse($apiResponse): Collection
    {
        return $this->createResponseObject($apiResponse);
    }

    /**
     * @param Iugu_SearchResult|array $iuguSearchResult
     * @return Collection
     */
    protected function createResponseObject($iuguSearchResult): Collection
    {
        if (! $iuguSearchResult instanceof Iugu_SearchResult) {
            return collect([]);
        }

        $responseKeys = $iuguSearchResult->results()[0]->keys();

        if (count($iuguSearchResult->results()) === 1) {
            $iuguResultObject = $iuguSearchResult->results()[0];
            $result[] = collect($responseKeys)->reduce(function ($resultObject, $key) use ($iuguResultObject) {
                $resultObject[$key] = $iuguResultObject->offsetGet($key);

                return $resultObject;
            }, []);

            return collect($result);
        }


        $result = collect($responseKeys)->map(function ($item) {
            $iuguResultObject = $item->results()[0];

            return collect($item)->reduce(function ($resultObject, $key) use ($iuguResultObject, $item) {
                $resultObject[$key] = $item->offsetGet($key);

                return $resultObject;
            }, []);
        });

        return collect($result);
    }
}
