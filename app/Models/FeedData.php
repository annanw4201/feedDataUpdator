<?php

namespace App\Models;

use Carbon\Carbon;

class FeedData
{

    private $data = null, $current_date = null;

    public function __construct($data, $date = "2020-11-26")
    {
        $this->data = $data ?? (object)[];
        $this->current_date = Carbon::createFromFormat("Y-m-d", $date);
    }

    public function currentDate() {
        return $this->current_date->format('Y-m-d');
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
                "aum" => $fundData->aum,
                "series" => []
            ];
            foreach ($series as $seriesId => $seriesData) {
                $latestNav = $seriesData->latest_nav ?? null;
                if (!$latestNav) continue;
                $date = Carbon::createFromFormat('Y-m-d', $latestNav->date) ?? null;
                if (!$date) continue;
                $res[$fundId]['series'][$seriesId] = [];
                if ($date < $this->current_date) {
                    $res[$fundId]['series'][$seriesId]['latest_nav'] = $latestNav;
                }
                else {
                    unset($res[$fundId]['series'][$seriesId]);
                }
            }
            if (!count($res[$fundId]["series"])) unset($res[$fundId]);
        }
        return $res;
    }

    public function updateData ($newDate, array $newData) {
        foreach ($newData as $fundId => $datum) {
            if ($this->data->$fundId ?? null) {
                $this->data->$fundId->aum = $datum['aum'];
                $newSeries = $datum['series'];
                foreach ($newSeries as $seriesId => $newSeriesData) {
                    $this->data->$fundId->series->$seriesId->latest_nav->date = $newDate;
                    $this->data->$fundId->series->$seriesId->latest_nav->value = $newSeriesData['latest_nav']['value'];
                }
            }
        }
    }
}
