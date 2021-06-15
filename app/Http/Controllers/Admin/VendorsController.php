<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorsRequest;
use App\Http\Requests\VendorsUpdateRequest;
use App\Models\language;
use App\Models\MainCategories;
use App\Models\vendors;
use App\Notifications\VendorCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index(){
        $vendors = vendors::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index',compact('vendors'));
    }

    public function create(){
        $vendors = vendors::selection();
        $categories = MainCategories::where('translation_of',0)->active()->get();
        return view('admin.vendors.create',compact('categories'));
    }

    public function store(VendorsRequest $request){
        try{
          // return $request;
            if(!$request->has('active'))
                 $request-> request->add(['active'=>0]);
            else
                $request-> request->add(['active'=>1]);

            $filepath = "";
            if ($request ->has('logo')) {
                $filepath = uploadimage('vendors', $request->logo);
            }

            $filepath="";
            if($request->has('logo')){
                $filepath = uploadimage('vendors',$request->logo);

            }

       $vendor =  vendors::create([
            'name' =>$request->name,
            'category_id' =>$request->category_id,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
           'password'=>bcrypt($request->password),
            'active'=>$request->active,
            'address'=>$request->address,
            'logo'=>$filepath,
           'latitude' =>$request -> latitude,
           'longitude' =>$request -> longitude,

           ]);
            Notification::send($vendor, new VendorCreate($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الإنشاء بنجاح']);





            //validation
        //insert to DB
        }catch (\Exception $ex){
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'عفوا لقد حدث خطأ يرجى المحاولة فيما بعد']);


        }

    }

    public function edit($id)
    {
        try {
            $vendor = vendors::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'المتجر دا مش موجود ممكن تجرب مرة تانية']);
            $categories = MainCategories::where('translation_of',0)->active()->get();

            return view('admin.vendors.edit', compact('vendor','categories'));


        }catch (\Exception $ex){
            return $ex;
    }
    }

    public function update($id, VendorsUpdateRequest $request){
       // return $request;
        //validation
        //update

        try{
       $vendor = vendors::Selection()->find($id);
       if(!$vendor)
           return redirect()->route('admin.vendors')->with(['error' => 'المتجر دا مش موجود ممكن تجرب مرة تانية']);
DB::beginTransaction();
       if($request->has('logo')) {
           $filepath = uploadImage('vendors', $request->logo);
           vendors::where('id', $id)
               ->update([
                   'logo' => $filepath,

               ]);
       }

            if(!$request->has('active'))
           $request-> request->add(['active'=>0]);
               else
                   $request-> request->add(['active'=>1]);


           $data = $request-> except('_token','id','logo','password');
            if ($request->has('password') && !is_null($request->  password)) {
                $data['password'] = $request->password;
           }
           vendors::where('id',$id)
           -> update($data);

            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);


       }catch (\Exception $ex){
            DB::rollBack();

            return $ex;
        }



    }

    public function changeStatus($id){
       $vendor= vendors::selection()->find($id);
       if(!$vendor)
           return redirect()->route('admin.vendors')->with(['error' => 'التاجر دا مش موجود']);


        $stat= $vendor->active==0 ? 1:0;
       $vendor ->update(['active'=>$stat]);

        return redirect()->route('admin.vendors')->with(['success' => 'تم تغيير الحالة ']);











    }

    public function destroy($id){
        try{
       $vendor= vendors::selection()->find($id);
       if(!$vendor)
           return redirect()->route('admin.vendors')->with(['error' => 'التاجر دا مش موجود']);

            $image = Str::after($vendor->logo, 'assets/' );
            $image = base_path('assets/'.$image);
            unlink($image); // delete from folder
            $vendor->delete();
        return redirect()->route('admin.vendors')->with(['success' => 'تم الحذف']);
        }catch (\Exception $ex){
            return $ex;
        }

    }




}
