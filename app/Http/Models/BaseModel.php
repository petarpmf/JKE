<?php
namespace App\Http\Models;

use App\Http\UuidTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/18/2015
 * Time: 3:07 PM
 */

class BaseModel extends Model{
    public $incrementing = false;
    use UuidTrait;
}