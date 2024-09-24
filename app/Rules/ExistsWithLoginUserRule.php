<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExistsWithLoginUserRule implements Rule
{
    protected $table;

    protected $ignoreUser;

    public function __construct($table, $ignoreUser = false)
    {
        $this->table = $table;
        $this->ignoreUser = $ignoreUser;
    }

    public function passes($attribute, $value)
    {

        if (gettype($value) === 'string') {
            $value = explode(',', $value);
        }

        $query = DB::table($this->table)->whereIn('id', $value);

        if (! $this->ignoreUser) {
            $query->where('user_id', Auth::id());
        }

        return $query->exists();
    }

    public function message()
    {

        return 'The selected :attribute is invalid.';
    }
}
