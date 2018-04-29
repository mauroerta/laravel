<?php

namespace App\Traits;

trait Slugable {
    /**
     * Make a unique slug for this object
     * @param  mixed $parameters it can be the desidered slug or an array of parameters:
     *               example of parameters array:
     *               [
     *                  'slug' => 'hello-world', // The desired slug
     *                  'except' => $this->id, // exclude this id from uniqueness checks
     *               ]
     * @return string the unique slug
     */
    public static function makeSlug($parameters = null) {
        $slug = is_string($parameters) ? $parameters : null;
        $except = null;

        if(is_array($parameters)) {
            $slug = isset($parameters['slug']) ? $parameters['slug'] : date('Y-m-d');
            $except = isset($parameters['except']) ? $parameters['except'] : $except;
        }

        $response = str_slug($slug);
        for($i = 1; self::where(self::getSlugColumnName(), $response)->where('id', '!=', $except)->exists(); $i++) {
            $response = str_slug("{$slug}-{$i}");
        }

        return $response;
    }

    /**
     * Find the object by the unique slug
     * @param  string $slug The slug of the object you want
     * @return Model
     */
    public static function findBySlug($slug) {
        return self::where(self::getSlugColumnName(), $slug)->first();
    }

    /**
     * The name of the slug column table
     * @return string The slug column name
     */
    public static function getSlugColumnName() {
        return config("me-trait.slugable.tables.{$this->table}.column", 'slug')
    }

    /**
     * The name of the desired column:
     *          - Crate new Article with title = 'Hello World'
     *          - The desired slug should be something like hello-world so the url can be for example:
     *            https://site.name/article/hello-world
     *          - So the desired column is the title, if it is another article with title = 'Hello Wordl'
     *            the slug should be something like 'hello-world-1'
     * @return string The desired column name
     */
    public static function getDesiredColumnName() {
        return config("me-trait.slugable.tables.{$this->table}.desired", 'title')
    }

    /**
     * Generate a slug for this object
     * @return boolean
     */
    public function generateSlug() {
        $this->{self::getSlugColumnName()} = self::makeSlug([
            'slug' => $this->{self::getDesiredColumnName()},
            'exept' => $this->id
        ]);

        return $this->save();
    }
}
