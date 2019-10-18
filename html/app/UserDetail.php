<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = 'user_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                    'user_id',
                    'user_type',
                    'first_name',
                    'last_name',
                    'companyInf',
                    'address',
                    'country',
                    'state',
                    'zipe_code',
                    'phone',
                    'telephone',
                    'created_at'
     ];
}
