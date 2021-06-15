<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\MainCategoryObserve;
use App\Models\Subcategory;

class MainCategories extends Model
{
    protected $table = 'main_categories';

    protected $fillable = [
       'id', 'translation_lang','translation_of','name','slug','photo','active','created_at','updated_at',
    ];

    public function scopeActive($query){
        return $query -> where('active',1);
    }
    public function ScopeSelection($query){
        return $query -> select('id','translation_lang','name','slug','photo','active','translation_of','created_at','updated_at');
    }
    public function getActive(){
        return $this -> active == 1 ? 'Active':'Deactive';
    }
    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }

    public function scopeCate_select($query){
        $cat_exc = ['active'=>1, 'translation_of' =>0];
        return $query -> where($cat_exc);

    }
    //get all translations

    public function categories(){
        return $this -> hasMany(self::class,'translation_of');
    }

    public function vendors(){
        return $this -> hasMany('App\Models\vendors','category_id','id');
    }

    public function SubCategories(){
        return $this -> hasMany(Subcategory::class,'category_id','id');
    }
    protected static  function boot(){
        parent::boot();
        MainCategories::observe(MainCategoryObserve::class);

    }

}
