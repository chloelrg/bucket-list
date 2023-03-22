<?php

namespace App\Services;

class Censurator
{
    public const BADWORDS = ['merde', 'caca', 'putain'];

    public function purify(string $text)
    {
        foreach (self::BADWORDS as $badword) {
            $nbetoile = str_repeat('*', strlen($badword));
            $text = str_ireplace($badword, $nbetoile, $text);
        }

        // $text_remplace = str_ireplace(self::BADWORDS,"***",$text);
        return $text;
    }
}
