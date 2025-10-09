<?php

class SanitizeService
{
    private static array $forbiddenCharacters = [ '"', "'",  '\\', ';']; 

    public static function isValidString(string $input, int $maxLength = 45): bool
    {
        if (strlen($input) > $maxLength) {
            return false;
        }

        foreach (self::$forbiddenCharacters as $char) {
            if (str_contains($input, $char)) {
                return false;
            }
        }

        return true;
    }

    public static function isValidDate(string $date): bool
    {
        // todo
        return false;
    }
}