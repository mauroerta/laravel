<?php
namespace ME\Traits;

trait Linkable
{
    /**
     * Get the url to the object
     * @param  boolean $only_path
     * @return string
     */
    public function url($only_path = false) {
        $class_parts = explode('\\', __CLASS__);
        $base_url = property_exists(self::class, 'linkable_base_url') ? $this->linkable_base_url : strtolower(end($class_parts));
        $field_name = property_exists(self::class, 'linkable_field_name') ? $this->linkable_field_name : 'slug';
        $url = $base_url . '/' . $this->{$field_name};
        return $only_path ? $url : url($url);
    }

    /**
     * Get the url used for actions like create, update ...
     * @return string
     */
    public function restUrl() {
        $prefix = config('me-trait.linkable.rest_base_url', 'api');
        return url($prefix . '/' . $this->url(true));
    }
}
