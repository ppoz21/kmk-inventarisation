<?php
namespace App\Util;

use Behat\Transliterator\Transliterator;
use function explode;
use function is_string;
use function ltrim;
use function urldecode;

abstract class SlugUtil{
    const SLUG_SEPARATOR = '-';
    const DOCUMENT_EXTENSION = '';

    /**
     * Explode slug into parts
     * @param string $slug Decoded slug
     * @param string $separator Parts separator
     *
     * @return array
     */
    public static function splitSlug(string $slug, string $separator = self::SLUG_SEPARATOR)
    {
        return explode($separator, urldecode($slug));
    }

    /**
     * Generate slug based on given parts
     * @param array|string $parts Array of parts or part to generate slug
     * @param string $separator Parts separator
     * @param string $suffix Slug suffix
     * @return string|bool False if input is invalid
     */
    public static function generateSlug(array|string $parts, string $separator = self::SLUG_SEPARATOR, string $suffix = self::DOCUMENT_EXTENSION): bool|string
    {
        $slug = '';
        if (is_string($parts))
            return Transliterator::transliterate($parts) . $suffix;

        foreach ($parts as $part)
            $slug .= $separator . Transliterator::transliterate($part);

        return ltrim($slug, $separator) . $suffix;
    }
}
