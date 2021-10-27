<?php

namespace Tests\Unit;

use App\Models\FeedData;

class FeedDataTest extends \Tests\TestCase
{
    private $testData;
    protected function setUp(): void
    {
        parent::setUp();
        // we only care about aum of fund, and latest_nav of each series right now, so we use this test data for testing
        $this->testData = [
            "fund1" => [
                "aum" => 2000,
                "name" => ["en" => "Test Fund1"],
                "series" => [
                    "A" => [
                        "latest_nav" => [
                            "date" => '2021-10-26',
                            "value" => 100
                        ]
                    ],
                    "B" =>  [
                        "latest_nav" => [
                            "date" => '2021-10-26',
                            "value" => 120
                        ]
                    ],
                ]
            ],
            "fund2" => [
                "aum" => 8000,
                "name" => ["en" => "Test Fund2"],
                "series" => [
                    "ETF" => [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 600
                        ]
                    ],
                    "F" =>  [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 720
                        ]
                    ],
                ]
            ]
        ];
    }

    public function testFeedDataModelCreation() {
        $testDataObject = json_decode(json_encode($this->testData));
        $cur_date = "2021-01-01";
        $stale_date = "2020-12-31";
        $feedData = new FeedData($testDataObject, $cur_date);
        self::assertEquals($cur_date, $feedData->currentDate());
        self::assertEquals($stale_date, $feedData->staleDate());
        self::assertEquals($testDataObject, $feedData->data());
        self::assertEquals($this->testData, $feedData->toArray());
        self::assertEquals(json_encode($this->testData), $feedData->json());
    }

    public function testGetStaleData() {
        $testDataObject = json_decode(json_encode($this->testData));

        // test no stale data
        $cur_date = "2021-01-01";
        $feedData = new FeedData($testDataObject, $cur_date);
        self::assertEquals([], $feedData->getStaleData());

        // test one stale data
        $cur_date = "2021-10-26";
        $feedData = new FeedData($testDataObject, $cur_date);
        $staleData = [
            "fund2" => [
                "aum" => 8000,
                "name" => "Test Fund2(fund2)",
                "series" => [
                    "ETF" => [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 600
                        ]
                    ],
                    "F" =>  [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 720
                        ]
                    ],
                ]
            ]
        ];
        self::assertEquals($staleData, $feedData->getStaleData());

        // test multiple stale data
        $cur_date = "2021-10-28";
        $feedData = new FeedData($testDataObject, $cur_date);
        $staleData = [
            "fund1" => [
                "aum" => 2000,
                "name" => "Test Fund1(fund1)",
                "series" => [
                    "A" => [
                        "latest_nav" => [
                            "date" => '2021-10-26',
                            "value" => 100
                        ]
                    ],
                    "B" =>  [
                        "latest_nav" => [
                            "date" => '2021-10-26',
                            "value" => 120
                        ]
                    ],
                ]
            ],
            "fund2" => [
                "aum" => 8000,
                "name" => "Test Fund2(fund2)",
                "series" => [
                    "ETF" => [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 600
                        ]
                    ],
                    "F" =>  [
                        "latest_nav" => [
                            "date" => '2021-10-24',
                            "value" => 720
                        ]
                    ],
                ]
            ]
        ];
        self::assertEquals($staleData, $feedData->getStaleData());
    }
}
