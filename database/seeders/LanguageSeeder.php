<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            // Línguas Moçambicanas
            ['name' => 'Emakhuwa', 'alternative_name' => 'Makhuwa', 'region' => 'Norte de Moçambique'],
            ['name' => 'Xichangana', 'alternative_name' => 'Changana', 'region' => 'Sul de Moçambique'],
            ['name' => 'Cisena', 'alternative_name' => 'Sena', 'region' => 'Centro de Moçambique'],
            ['name' => 'Elomwe', 'alternative_name' => 'Lomwe', 'region' => 'Norte de Moçambique'],
            ['name' => 'Cinyungwe', 'alternative_name' => 'Nyungwe', 'region' => 'Centro de Moçambique (Tete)'],
            ['name' => 'Echuwabo', 'alternative_name' => 'Chuwabo', 'region' => 'Centro de Moçambique (Zambézia)'],
            ['name' => 'Xirhonga', 'alternative_name' => 'Ronga', 'region' => 'Sul de Moçambique (Maputo)'],
            ['name' => 'Shimakonde', 'alternative_name' => 'Makonde', 'region' => 'Norte de Moçambique (Cabo Delgado)'],
            ['name' => 'Ciyao', 'alternative_name' => 'Yao', 'region' => 'Norte de Moçambique (Niassa)'],
            
            // Línguas Internacionais / Oficiais
            ['name' => 'Português', 'alternative_name' => 'Portuguese', 'region' => 'Moçambique / Global'],
            ['name' => 'Inglês', 'alternative_name' => 'English', 'region' => 'Global'],
            ['name' => 'Francês', 'alternative_name' => 'French', 'region' => 'Global'],
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(
                ['name' => $lang['name']],
                $lang
            );
        }
    }
}