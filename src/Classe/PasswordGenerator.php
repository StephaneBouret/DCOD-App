<?php

namespace App\Classe;

class PasswordGenerator
{
    /**
     * @var string
     */
    public function generateRandomStrongPassword(int $length)
    {
        $uppercaseLetters = $this->generateCharactersWithCharCodeRange([65, 90]);

        $lowercaseLetters = $this->generateCharactersWithCharCodeRange([97, 122]);

        $numbers = $this->generateCharactersWithCharCodeRange([48, 57]);

        $symbols = $this->generateCharactersWithCharCodeRange([33, 47, 58, 64, 91, 96, 123, 126]);

        $allCharacters = array_merge($uppercaseLetters, $lowercaseLetters, $numbers, $symbols);

        $isArrayShuffled = shuffle($allCharacters);

        if (!$isArrayShuffled) {
            throw new \LogicException("La génération d'un mot de passe aléatoire a échoué, veuillez réessayer.");
        }
            
        return implode('', array_slice($allCharacters, 0, $length));
    }

    private function generateCharactersWithCharCodeRange(array $range)
    {
        if (count($range) === 2) {
            return range(chr($range[0]), chr($range[1]));
        } else {
            // $chunkAsciiCodes = array_chunk($range, 2);

            // $specialCharactersChunks = array_map(fn($range) => range(chr($range[0]), chr($range[1])), $chunkAsciiCodes);

            // $allSpecialCharacters = array_merge(...$specialCharactersChunks);

            return array_merge(...array_map(fn ($range) => range(chr($range[0]), chr($range[1])), array_chunk($range, 2)));
        }
    }
}
