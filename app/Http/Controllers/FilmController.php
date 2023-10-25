<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class FilmController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        $movies = $movies->sortBy('year');

        $producers = [];

        foreach ($movies as $movie) {
            if (!empty($movie->producers)) {
                $producerNames = explode(',', $movie->producers);

                foreach ($producerNames as $producerName) {
                    $producer = trim($producerName);

                    if (!isset($producers[$producer])) {
                        $producers[$producer] = [
                            'previousWin' => $movie->year,
                            'followingWin' => null,
                            'maxInterval' => 0,
                            'fastestWin' => PHP_INT_MAX,
                            'winCount' => 1,
                        ];
                    } else {
                        $interval = $movie->year - $producers[$producer]['previousWin'];

                        if (is_null($producers[$producer]['followingWin']) && $interval > 1) {
                            $producers[$producer]['followingWin'] = $movie->year - 1;
                        }

                        $producers[$producer]['previousWin'] = $movie->year;

                        if ($interval < $producers[$producer]['fastestWin']) {
                            $producers[$producer]['fastestWin'] = $interval;
                        }

                        if ($interval > $producers[$producer]['maxInterval']) {
                            $producers[$producer]['maxInterval'] = $interval;
                        }

                        $producers[$producer]['winCount']++;
                    }
                }
            }
        }

        $minProducers = [];
        $maxProducers = [];

        foreach ($producers as $producer => $data) {
            if ($data['winCount'] > 1) {
                if ($data['fastestWin'] === PHP_INT_MAX) {
                    $data['fastestWin'] = null;
                }

                $minProducers[] = [
                    'producer' => $producer,
                    'interval' => $data['fastestWin'],
                    'previousWin' => $data['previousWin'],
                    'followingWin' => $data['followingWin'],
                ];

                $maxProducers[] = [
                    'producer' => $producer,
                    'interval' => $data['maxInterval'],
                    'previousWin' => $data['previousWin'],
                    'followingWin' => $data['followingWin'],
                ];
            }
        }

        $result = [
            'min' => $minProducers,
            'max' => $maxProducers,
        ];

        return response()->json($result);
    }
}
