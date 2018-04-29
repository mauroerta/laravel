<?php
namespace ME\Traits;

use Auth;
use App\User;

trait Draftable {
    public static DRAFTED_AT = config('me_trait.drafted_at_column', 'drafted_at');
    public static DRAFTED_BY = config('me_trait.drafted_by_column', 'drafted_by');

    /**
     * Get all the drafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function drafted() {
        return self::where(self::DRAFTED_AT, '!=', null)->get();
    }

    /**
     * Get all the undrafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function undrafted() {
        return self::where(self::DRAFTED_AT, null)->get();
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

        $this->drafted_at = date('Y-m-d H:i');
        $this->drafted_by = Auth::user()->id;

        return true;
    }

    /**
     * Undraft the current object
     * @return boolean
     */
    public function undraft() {
        if(Auth::guest()) return false;

        if(!Auth::user()->isAdmin()) return false;

        $this->{self::DRAFTED_AT} = null;
        $this->{self::DRAFTED_BY} = Auth::user()->id;

        return true;
    }

    /**
     * Check if the current object is drafted
     * @return boolean
     */
    public function isDrafted() {
        return $this->{self::DRAFTED_AT} != null;
    }

    /**
     * Get the user who draft the object
     * @return User
     */
    public function draftedBy() {
        return $this->belongsTo(User::class, self::DRAFTED_BY);
    }

    /**
     * Get the name of the "drafted at" column.
     *
     * @return string
     */
    public function getDraftedAtColumn()
    {
        return defined('static::DRAFTED_AT') ? static::DRAFTED_AT : 'drafted_at';
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
        return defined('static::DRAFTED_BY') ? static::DRAFTED_BY : 'drafted_by';
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
}
