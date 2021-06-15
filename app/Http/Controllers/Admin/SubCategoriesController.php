<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\subcategoryRequest;
use App\Models\MainCategories;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;


class SubCategoriesController extends Controller
{
    public function index(){
        $subcategories=  SubCategory::where('translation_lang',get_default_lang())->selection()->get();
        return view('admin.subcategories.index',compact('subcategories'));
    }

    public function create(){
       $main_cats = MainCategories::Cate_select()-> selection()-> get();

        return view('admin.subcategories.create', compact('main_cats'));
    }

    public function store(subcategoryRequest $request){
      //  return $request;
        try {
          $sub_cats =  collect($request -> subcategory);
            $filter = $sub_cats -> filter(function ($value,$key){
                return $value['abbr'] == get_default_lang();

            });

             if($request -> has('photo'))
                 $filepath = uploadImage('subcategories',$request -> photo);

           $sub_all = array_values($filter -> all()) [0];

           //insert into DB

            DB::beginTransaction();

           $the_id= SubCategory::insertGetId([
                'parent_id'=>0,
                'category_id'=> $request -> category_id,
                'translation_lang'=>$sub_all['abbr'],
                'translation_of'=>0,
                'name'=>$sub_all['name'],
                'slug'=>$sub_all['name'],
                'photo'=>$filepath,

            ]);

            $sub_excepts = $sub_cats -> filter(function ($value,$key) {
                return $value['abbr'] !== get_default_lang();
            });

                if(isset($sub_excepts) && $sub_excepts -> count() ){
                      $array_subcat = [];
                    foreach($sub_excepts as $sub_except )
                        $array_subcat[]= ([
                            'parent_id'=>0,
                            'category_id'=> $request -> category_id,
                            'translation_lang'=>$sub_except['abbr'],
                            'translation_of'=>$the_id,
                            'name'=>$sub_except['name'],
                            'slug'=>$sub_except['name'],
                            'photo'=>$filepath,

                        ]);
                }
                    SubCategory::insert($array_subcat);
            DB::commit();
            return redirect()->route('admin.subcategories')-> with(['success'=> 'تم الحفظ بنجاح يامعلم المعلمين']);


        }catch(\Exception $ex){
            DB::rollback();

            return $ex;
        }

    }
    public function edit($id){
        //subcategories with its translations

        $subcategories = SubCategory::with('subcategories')->selection()->find($id);
        if(!$subcategories)
            return redirect()-> route('admin.subcategories')-> with(['error'=>'هذا القسم غير موجود']);
        return view('admin.subcategories.edit',compact('subcategories'));

    }
    public function update($id, subcategoryRequest $request){
        //validation
        // update
        $sub_cat= SubCategory::find($id);
        if(!$sub_cat)
        return redirect()-> route('admin.subcategories')-> with(['error'=>'هذا القسم غير موجود']);

        $subcat_array = array_values($request-> subcategory) [0];

       if (!$request-> has('subcategory.0.active'))
            $request->request->add(['active' => 0]);
        else
            $request->request->add(['active' => 1]);
       $active = $request -> active;
//save photo
        if($request -> has('photo'))
            $photo = uploadImage('subcategories',$request-> photo);

        SubCategory::where('id',$id)->update([
            'name' => $subcat_array['name'],
            'active'=> $active,
            'photo'=>$photo,


        ]);


        return redirect()-> route('admin.subcategories')-> with(['success'=>'تم التحديث']);

    }

    public function changeStatus($id){

        $sub_cat= SubCategory::find($id);
        if(!$sub_cat)
            return redirect()-> route('admin.subcategories')-> with(['error'=>'هذا القسم غير موجود']);

        $status = $sub_cat ->active == 0 ? 1:0;
        $sub_cat ->update(['active'=> $status]);
                return redirect()-> route('admin.subcategories')-> with(['success'=>'تم تغيير الحالة']);


    }

    public function destroy($id){
       $sub_cat= SubCategory::find($id);
        if(!$sub_cat)
            return redirect()-> route('admin.subcategories')-> with(['error'=>'هذا القسم غير موجود']);

        $image = Str::after($sub_cat->photo, 'assets/' );
        $image = base_path('assets/'.$image);
        unlink($image); // delete from folder

        $sub_cat->subcategories()->delete();
        $sub_cat->delete();

        return redirect()-> route('admin.subcategories')-> with(['success'=>'تم الحذف']);




    }






}
