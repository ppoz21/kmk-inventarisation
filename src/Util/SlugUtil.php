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
    public static function splitSlug($slug, $separator = self::SLUG_SEPARATOR)
    {
        return explode($separator, urldecode($slug));
    }

    /**
     * Generate slug based on given parts
     * @param string|array $parts Array of parts or part to generate slug
     * @param string $separator Parts separator
     * @param string $suffix Slug suffix
     * @return string|bool False if input is invalid
     */
    public static function generateSlug($parts, $separator = self::SLUG_SEPARATOR, $suffix = self::DOCUMENT_EXTENSION)
    {
        $slug = '';
        if (is_string($parts))
            return Transliterator::transliterate($parts) . $suffix;

        foreach ($parts as $part)
            $slug .= $separator . Transliterator::transliterate($part);

        return ltrim($slug, $separator) . $suffix;
    }
}
