<?php

namespace App\Http\Controllers;

use App\Services\FeedDataService;
use Illuminate\Http\Request;

class FeedDataController extends Controller
{
    private $feedDataService = null;

    public function __construct()
    {
        $this->feedDataService = new FeedDataService();
    }

    public function index(Request $request) {
        $data = [];
        $feedData = $this->feedDataService->getFeedData();
        $data["staleData"] = $feedData->getStaleData();
        $data["feedData"] = $feedData->data();
        return view("feed_data", ["data" => json_encode($data)]);
    }
}
