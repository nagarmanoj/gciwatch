<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Product;

use App\ProductType;

use App\Category;

use App\SubCategory;

use App\SubSubCategory;

use App\Brand;

use App\User;

use Auth;

use App\ProductsImport;

use App\ProductsExport;

use App\ProductsDownload;

use PDF;

use Excel;

use Illuminate\Support\Str;



class ProductBulkUploadController extends Controller

{

    public function index()

    {

        if (Auth::user()->user_type == 'seller') {

            return view('frontend.user.seller.product_bulk_upload.index');

        }

        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {

            return view('backend.product.bulk_upload.index');

        }

    }



    // public function export(Request $request){

    //     $ids=$request->ids;

    //     // echo $ids; exit();

    //     $proID = json_decode($ids, TRUE);

    //     // echo $proID; exit();

    //         //   $proID = array($ids);

    //         //   echo $proID; exit();

    //           $fetchLiaat = new ProductsExport($ids);

    //         return Excel::download($fetchLiaat, 'products.xlsx');

    //     // //return Excel::download(new ProductsExport, 'products.xlsx');

    // }



    public function pdf_download_category()

    {

        $categories = Category::all();



        return PDF::loadView('backend.downloads.category',[

            'categories' => $categories,

        ], [], [])->download('category.pdf');

    }



    public function pdf_download_brand()

    {

        $brands = Brand::all();



        return PDF::loadView('backend.downloads.brand',[

            'brands' => $brands,

        ], [], [])->download('brands.pdf');

    }



    public function pdf_download_seller()

    {

        $users = User::where('user_type','seller')->get();



        return PDF::loadView('backend.downloads.user',[

            'users' => $users,

        ], [], [])->download('user.pdf');



    }



    public function bulk_upload(Request $request)

    {

      $qury= Excel::import(new ProductsImport, $request->file('bulk_file')->store('bulk_file'));

        return redirect()->back()->with('success','Data Imported Successfully');

    }





            public function export(Request $request){

                  $ids = $request->checked_id;

                  $proID = json_decode($ids, TRUE);

                  $fetchLiaat = new ProductsExport($proID);

                  // dd($fetchLiaat);
                  $proID = json_decode($ids, TRUE);
                  $fetchLiaat = new ProductsExport($proID);
                  $dt = new \DateTime();
                  $curntDate = $dt->format('m-d-Y');
                return Excel::download($fetchLiaat, 'ProExport_'.$curntDate.'.xlsx');

            }



            public function DownloadProTypeCsv(Request $Request)

            {

              $type = $Request->P_type_upload;

              $proCreateArr = new ProductsDownload($type);
              return Excel::download($proCreateArr, 'sample_products_'.$type.'.csv');

            }



}
