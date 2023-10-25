<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use SplFileObject;

class ImportMovies extends Command
{
    protected $signature = 'import:movies';
    protected $description = 'Import movies from CSV file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filename = storage_path('app/movielist.csv'); // Caminho para o arquivo CSV.

        $file = new SplFileObject($filename, 'r');
        $file->setFlags(SplFileObject::READ_CSV);
        
        $skippedFirstLine = false; // Variável para rastrear se a primeira linha foi pulada.
        
        foreach ($file as $row) {
            // Verifique se a linha não está vazia.
            if (!empty($row[0])) {
                // Se a primeira linha não foi pulada, defina a variável $skippedFirstLine como true e pule esta iteração.
                if (!$skippedFirstLine) {
                    $skippedFirstLine = true;
                    continue;
                }
        
                $data = str_getcsv($row[0], ';');

                if (count($data) >= 5) {
                    list($year, $title, $studios, $producers, $winner) = $data;
        
                    Movie::create([
                        'year' => $year,
                        'title' => $title,
                        'studios' => $studios,
                        'producers' => $producers,
                        'winner' => $winner,
                    ]);
                }
            }
        }
        


        $this->info('Movies imported successfully.');
    }
}
