<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaincategoriesRequest;
use App\Models\MainCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Str;

class MainCategoriesController extends Controller
{
    public function index(){
       $default_lang = get_default_lang();
      $categories =  MainCategories::where('translation_lang',$default_lang) -> selection() ->get();
       return view('admin.maincategories.index',compact('categories'));

    }
    public function create()
    {
        return view('admin.maincategories.create');
    }
    public function store(MaincategoriesRequest $request)
    {
      // return $request;
        try {

        $main_categories = collect($request -> category);
         $filter = $main_categories -> filter(function($value,$key){
            return $value['abbr'] == get_default_lang();

        });

          $default_category = array_values($filter -> all()) [0];


         $filepath = "";
         if ($request ->has('photo')) {
               $filepath = uploadimage('maincategories', $request->photo);
         }
              DB::begintransaction();
             $default_category_id = MainCategories::insertGetId([
             'translation_lang' => $default_category['abbr'],
             'translation_of' => 0,
             'name'=> $default_category['name'],
             'slug'=> $default_category['name'],
             'photo'=> $filepath,

                 ]);

              $categories = $main_categories -> filter(function($value,$key){
                 return $value['abbr'] !== get_default_lang();

             });


            if(isset($categories) && $categories -> count())
            {
                $categories_arr=[];
                foreach ($categories as $category) {
                    $categories_arr[]= [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name'=> $category['name'],
                        'slug'=> $category['name'],
                        'photo'=> $filepath,

                    ];
                }


                MainCategories::insert($categories_arr);
            }
             DB::commit();

            return redirect()->route('admin.maincategories')-> with(['success'=> 'تم الحفظ بنجاح يامعلم']);
         } catch(\Exception $ex){
             DB::rollback();
                return redirect()->route('admin.maincategories')-> with(['erorr'=> 'عفوا لقد حدث خطأ يرجى المحاولة فيما بعد']);

            }
         }

         public function edit($mainCat_id)
         {
             //get specific categories and its translation
             $mainCategory = maincategories::with('categories')
                 ->selection()
                 ->find($mainCat_id);
             if (!$mainCategory)
                 return redirect()->route('admin.maincategories')->with(['error' => 'هذا الحقل غير موجود يابرنس']);
             return view('admin.maincategories.edit', compact('mainCategory'));


         }
         public function update($mainCat_id,MainCategoriesRequest $request){
      // return $request;
       try {
           $main_category = MainCategories::find($mainCat_id);

           if (!$main_category)
               return redirect()->route('admin.maincategories')->with(['error' => 'هذا الحقل غير موجود يابرنس']);

           $category = array_values($request->category) [0];
            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

           MainCategories::where('id', $mainCat_id)
               ->update([
                   'name' => $category['name'],
                   'active' => $request->active,


               ]);
            //save image
           if ($request ->has('photo')) {
               $filepath = uploadimage('maincategories', $request->photo);

               MainCategories::where('id', $mainCat_id)
                   ->update([
                       'photo' => $filepath,
                   ]);
           }

            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بجناح']);

        }catch(\Exception $ex) {


           return redirect()->route('admin.maincategories')->with(['erorr' => 'عفوا لقد حدث خطأ يرجى المحاولة فيما بعد']);
       }

       }

       public function destroy($id){

        try{
            $maincategory = MainCategories::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

           $vendors= $maincategory->vendors();
            if(isset($vendors) && $vendors->count()>0){
                return redirect()->route('admin.maincategories')->with(['error' => 'مينفعش تحذف القسم دا للأسف ']);

            }

            $image = Str::after($maincategory->photo, 'assets/' );
            $image = base_path('assets/'.$image);
            unlink($image); // delete from folder

           $maincategory -> categories()-> delete();
           $maincategory->delete();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف ']);



        }catch(\Exception $ex){
            return $ex;



        }

       }

       public function changeStatus($id){

        $maincategory = MainCategories::find($id);
        if(!$maincategory){
            return redirect()->route('admin.maincategories')->with(['success' => 'القسم دا مش موجود ']);
        }
        $status = $maincategory -> active == 1 ? 0:1;

           $maincategory-> update(['active'=>$status]);
           return redirect()->route('admin.maincategories')->with(['success' => 'تم تغيير الحالة ']);



       }





}



