<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static
 */
class language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'abbr','local','name','direction','active','created_at','updated_at',
    ];

    public function scopeActiveabbr($query){
        $abbract = ['active'=>1];
        return $query -> where($abbract);
    }


    public function scopeSelection($query){
        return $query -> select('id', 'abbr','name', 'direction', 'active','created_at','updated_at');

    }
    public function getActive(): string
    {
       return $this -> active == 1 ? 'Active' : 'Deactive';

    }
}
