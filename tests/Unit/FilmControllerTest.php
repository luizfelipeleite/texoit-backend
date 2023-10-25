<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;

class FilmControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetWorstFilms()
    {
        // Insira alguns dados de exemplo no banco de dados.
        Movie::create([
            'year' => 2000,
            'title' => 'Movie 1',
            'studios' => 'Studio 1',
            'producers' => 'Producer A',
            'winner' => 'yes',
        ]);

        Movie::create([
            'year' => 2010,
            'title' => 'Movie 2',
            'studios' => 'Studio 2',
            'producers' => 'Producer A',
            'winner' => 'yes',
        ]);

        Movie::create([
            'year' => 2005,
            'title' => 'Movie 3',
            'studios' => 'Studio 3',
            'producers' => 'Producer C',
            'winner' => 'yes',
        ]);

        Movie::create([
            'year' => 2020,
            'title' => 'Movie 4',
            'studios' => 'Studio 4',
            'producers' => 'Producer C',
            'winner' => 'yes',
        ]);

        // Faça uma solicitação para a rota API.
        $response = $this->get('/api/worst-films');

        $response->assertStatus(200);

        $response->assertJson([
            'min' => [
                [
                    'producer' => 'Producer A',
                    'interval' => 10,
                    'previousWin' => 2000,
                    'followingWin' => 2010,
                ],
            ],
            'max' => [
                [
                    'producer' => 'Producer C',
                    'interval' => 15,
                    'previousWin' => 2005,
                    'followingWin' => 2020,
                ],
            ],
        ]);
    }
}
