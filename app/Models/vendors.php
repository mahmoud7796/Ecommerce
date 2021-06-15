<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class vendors extends Model
{
    use Notifiable;
    protected $table = 'vendors';
    protected $fillable = ['id','name','logo','mobile','address','email','password','category_id','latitude','longitude','active','created_at','updated_at'];
    protected $hiddden = ['category_id','password'];

public function scopeActive($query){
    return $query -> where('active',1) ;
}

    public function getlogoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }
    public function ScopeSelection($query){
        return $query -> select('id','name','logo','mobile','address','email','password','category_id','latitude','longitude','active','created_at','updated_at');
    }
    public function getactive(){
    return $this-> active ==1 ? 'active':'deactive';
    }
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
          return  $this->attributes['password'] = bcrypt($password);
        }
    }

    public function category(){
     return $this -> belongsTo('App\Models\MainCategories','category_id','id');
    }

    public function ScopeTranslationof($query){
     }





}
