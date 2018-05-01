<?php

if(! function_exists('me_replace')) {
    /**
     * Replace the placeholders in the helper.php file
     * @param  string $text
     * @param  array $keywords
     * @return string
     */
    function me_replace($text, $keywords = []) {
        foreach ($keywords as $key => $value) {
            $text = str_replace("{{{$key}}}", $value, $text);
        }
        return $text;
    }
}
