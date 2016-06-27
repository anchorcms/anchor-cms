<?php

namespace Anchorcms;

class Filters
{
    // why do i have to do this??? why is the default value ignored??
    public static function withDefaults(array $input, array $filters): array
    {
        $input = filter_var_array($input, $filters);

        foreach (array_keys($filters) as $key) {
            // if the value is null and there is a default value, use it
            if (null === $input[$key] && isset($filters[$key]['options']['default'])) {
                $input[$key] = $filters[$key]['options']['default'];
            }
        }

        return $input;
    }

    public static function withDefault(array $input, string $key, int $filter, array $options = []): string
    {
        $value = filter_var($input[$key] ?? null, $filter, $options);

        if (null === $value && isset($options['options']['default'])) {
            $value = $options['options']['default'];
        }

        return $value;
    }
}
