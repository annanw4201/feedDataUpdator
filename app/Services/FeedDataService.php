<?php

namespace App\Services;

use App\Models\FeedData;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FeedDataService
{
    private $feedDataUrl = 'https://purposecloud.s3.amazonaws.com/challenge-data.json';

    public function getFeedData() {
        try {
            $response = Http::acceptJson()->get($this->feedDataUrl)->throw()->object();
            return new FeedData($response);
        } catch (RequestException $exception) {
            return (object)[];
        }
    }
}
