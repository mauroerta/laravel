<?php
namespace ME\Traits;

use Auth;

trait Draftable {
    /**
     * Get all the drafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function drafted() {
        return self::whereNotNull(self::DRAFTED_AT())->get();
    }

    /**
     * Get all the undrafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function undrafted() {
        return self::whereNull(self::DRAFTED_AT())->get();
    }

    /**
     * Get all the active items (@see the undrafted method)
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function active() {
        return self::undrafted();
    }

    /**
     * Draft the current object
     * @return boolean
     */
    public function draft() {
        if(Auth::guest()) return false;

        if(!Auth::user()->isAdmin()) return false;

        $this->{$this->getDraftedAtColumn()} = date('Y-m-d H:i');
        $this->{$this->getDraftedByColumn()} = Auth::user()->id;
        $this->save();

        return true;
    }

    /**
     * Undraft the current object
     * @return boolean
     */
    public function undraft() {
        if(Auth::guest()) return false;

        if(!Auth::user()->isAdmin()) return false;

        $this->{$this->getDraftedAtColumn()} = null;
        $this->{$this->getDraftedByColumn()} = Auth::user()->id;
        $this->save();

        return true;
    }

    /**
     * Check if the current object is drafted
     * @return boolean
     */
    public function isDrafted() {
        return $this->{$this->getDraftedAtColumn()} != null;
    }

    /**
     * Get the user who draft the object
     * @return User
     */
    public function draftedBy() {
        return $this->belongsTo(config('me_trait.user_model', 'App\User'), $this->{$this->getDraftedAtColumn()});
    }

    /**
     * Get the name of the "drafted at" column.
     *
     * @return string
     */
    public function getDraftedAtColumn()
    {
        return self::DRAFTED_AT();
    }

    /**
     * Get the fully qualified "drafted at" column.
     *
     * @return string
     */
    public function getQualifiedDraftedAtColumn()
    {
        return $this->qualifyColumn($this->getDraftedAtColumn());
    }

    /**
     * Get the name of the "drafted by" column.
     *
     * @return string
     */
    public function getDraftedByColumn()
    {
        return self::DRAFTED_BY();
    }

    /**
     * Get the fully qualified "drafted by" column.
     *
     * @return string
     */
    public function getQualifiedDraftedByColumn()
    {
        return $this->qualifyColumn($this->getDraftedByColumn());
    }

    /**
     * Get the class name of the User Model
     *
     * @return string
     */
    public function getUserModelClass() {
        return config('me_trait.user_model', 'App\User');
    }

    /**
     * Get the name of the "drafted at" column.
     *
     * @return string
     */
    public static function DRAFTED_AT() {
        return config('me_trait.drafted_at_column', 'drafted_at');
    }

    /**
     * Get the name of the "drafted by" column.
     *
     * @return string
     */
    public static function DRAFTED_BY() {
        return config('me_trait.drafted_by_column', 'drafted_by');
    }
}
