<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueWithLoginUserRule implements Rule
{
    protected $table;
    protected $column;
    protected $model;
    protected $ignoreId;
    protected $ignoreUser;
    protected $value;
    
    public function __construct($table, $column, $model, $ignoreId = null, $ignoreUser = true)
    {
        $this->table = $table;
        $this->column = $column;
        $this->model = $model;
        $this->ignoreId = $ignoreId;
        $this->ignoreUser = $ignoreUser;
    }

    public function passes($attribute, $value)
    {
        $this->value = $value;

        $query = DB::table($this->table)->where($this->column, $value);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }
        
        if ($this->ignoreUser) {
            $query->where('user_id', Auth::id());
        };

        return !$query->exists();
    }

    public function message()
    {

        return 'You already have a ' . strtolower($this->model) . ' with ' . $this->value . '.';
    }
}
