<?php

namespace App\Models;

use App\Models\MainCategories;
use App\Observers\MainCategoryObserve;
use App\Observers\SubcategoryObserver;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_category';
    protected $guarded = [''];
    public function Scopeselection($query){
        return $query -> select();
    }

    public function getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/'.$val):"";
    }
    public function getActive(){
        return $this -> active == 1 ? 'Active':'Deactive';
    }
    public function subcategories(){
        return $this -> hasMany(self::class,'translation_of');
    }
    public function ScopeActive($query){
        return $query-> where('active',1);

    }

public function maincategory(){
        return $this -> belongsTo(MainCategories::class,'category_id','id');
}




}





