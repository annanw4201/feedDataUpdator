<?php

namespace App\Models;

use Carbon\Carbon;

class FeedData
{

    private $data = null, $current_date = null;

    public function __construct($data)
    {
        $this->data = $data ?? (object)[];
        $this->current_date = Carbon::createFromFormat("Y-m-d", "2020-11-26");
    }

    public function json() {
        return json_encode($this->data);
    }

    public function data() {
        return $this->data;
    }

    public function toArray() {
        return (array)$this->data;
    }

    public function getStaleData() {
        $res = [];
        foreach ($this->data as $fundId => $fundData) {
            $series = $fundData->series ?? null;
            if (!$series) continue;
            $fundName = $fundData->name->en;
            $res[$fundId] = [
                "name" => "$fundName($fundId)",
                "series" => [],
                "aum" => $fundData->aum
            ];
            foreach ($series as $seriesId => $seriesData) {
                $latestNav = $seriesData->latest_nav ?? null;
                if (!$latestNav) continue;
                $date = Carbon::createFromFormat('Y-m-d', $latestNav->date) ?? null;
                if (!$date) continue;
                $res[$fundId]['series'][$seriesId] = [];
                if ($date < $this->current_date) {
                    $res[$fundId]['series'][$seriesId] = $seriesData;
                }
                else {
                    unset($res[$fundId]['series'][$seriesId]);
                }
            }
            if (!count($res[$fundId]["series"])) unset($res[$fundId]);
        }
        return $res;
    }
}
