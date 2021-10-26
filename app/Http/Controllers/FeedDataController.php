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

        return view("feed_data", ["data" => $this->feedDataService->getFeedData()]);
    }
}
