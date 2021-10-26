<?php

namespace App\Http\Controllers;

use App\Services\FeedDataService;
use Illuminate\Http\Request;

class FeedDataController extends Controller
{
    private $feedDataService = null;

    public function __construct()
    {
        if (!$this->feedDataService) $this->feedDataService = new FeedDataService();
    }

    public function index(Request $request) {
        $data = [];
        $feedData = $this->feedDataService->getFeedData();
        $data["staleData"] = $feedData->getStaleData();
        $data["feedData"] = $feedData->data();
        return view("feed_data", ["data" => json_encode($data)]);
    }

    public function updateStaleData(Request $request) {
        $newData = $request->newData;
        $newDate = $request->newDate;
        $this->saveAsCsv($newDate, $newData);
        $this->saveAsJson($newDate, $newData);
        return response()->json(["success" => true], 200);
    }

    private function saveAsJson($newDate, $data) {
        $feedData = $this->feedDataService->getFeedData();
        if (!$feedData) return true; // empty data return
        $feedData->updateData($newDate, $data);
        $fileName = time().'_feed_data.json';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $feedData->json());
        fclose($fp);
        return true;
    }

    private function saveAsCsv($newDate, $data) {
        if (!count($data)) return true; // empty data return
        $fileName = time().'_feed_data.csv';

        $file = fopen($fileName, 'w');
        fputcsv($file, array("New Date", $newDate));

        foreach ($data as $fundId => $datum) {
            $aum = $datum['aum'];
            $fundName = $datum['name'];
            $row = array($fundName, $aum);
            fputcsv($file, $row); // append first row with fundName, and its aum

            $series = $datum['series'];
            foreach ($series as $seriesId => $seriesData) {
                $latestNav = $seriesData['latest_nav'];
                $row = array('', $seriesId, $latestNav['value']);
                fputcsv($file, $row); // append row with seriesId, and its NAV value
            }
            $row = array();
            fputcsv($file, $row); // append empty row to separate fundIds
        }
        fclose($file);

        return true;
    }
}
