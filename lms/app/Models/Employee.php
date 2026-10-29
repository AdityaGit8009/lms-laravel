<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  protected $table = 'tbl_employee';
  protected $primaryKey = 'emp_id';
  public $timestamps = false;

  protected $fillable = [
    'emp_name',
    'emp_mobile',
    'emp_email',
    'emp_password',
    'emp_gender',
    'emp_role',
    'status',
    'emp_del_status',
  ];
}
