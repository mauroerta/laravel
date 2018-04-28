<?php
namespace Mauro\Traits;

use Auth;
use App\User;

trait Draftable {
    /**
     * Get all the drafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function drafted() {
        return self::where('drafted_at', '!=', null)->get();
    }

    /**
     * Get all the undrafted items
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function undrafted() {
        return self::where('drafted_at', null)->get();
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

        $this->drafted_at = null;
        $this->drafted_by = Auth::user()->id;

        return true;
    }

    /**
     * Check if the current object is drafted
     * @return boolean
     */
    public function isDrafted() {
        return $this->drafted_at != null;
    }

    /**
     * Get the user who draft the object
     * @return User
     */
    public function draftedBy() {
        return $this->belongsTo(User::class, 'drafted_by');
    }
}
