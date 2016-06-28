<?php

namespace Anchorcms;

/**
 * Wrapper for filter_var_array and filter_var to remove null values
 * and replace them with their default values if one is set otherwise
 * a empty string is used in its place
 */
class Filters
{
    /**
     * Return filtered array from input array falling back to the option default
     * of a empty string.
     */
    public static function withDefaults(array $input, array $filters): array
    {
        $input = filter_var_array($input, $filters);

        foreach (array_keys($filters) as $key) {
            // if the value is null and there is a default value, use it
            if (null === $input[$key]) {
                $input[$key] = $filters[$key]['options']['default'] ?? '';
            }
        }

        return $input;
    }

    public static function withDefault(array $input, string $key, int $filter, array $options = []): string
    {
        $value = filter_var($input[$key] ?? null, $filter, $options);

        if (null === $value) {
            $value = $options['options']['default'] ?? '';
        }

        return $value;
    }
}
