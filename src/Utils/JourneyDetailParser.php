<?php

namespace RejseplanApi\Utils;

class JourneyDetailParser
{
    public static function parseJourneyDetailUrl($url): ?string
    {
        $query = parse_url(urldecode($url), PHP_URL_QUERY);
        parse_str($query, $queryDetails);
        if (isset($queryDetails['ref'])) {
            return urlencode($queryDetails['ref']);
        }

        return null;
    }
}
