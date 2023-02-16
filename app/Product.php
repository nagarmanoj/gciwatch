<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;

class Product extends Model {

    protected $fillable = [
        'name', 'added_by', 'user_id', 'category_id', 'brand_id', 'video_provider', 'video_link', 'unit_price','partner','metal','model','weight',
        'purchase_price', 'unit', 'slug', 'colors', 'choice_options', 'variations', 'thumbnail_img', 'meta_title', 'description','size','productcondition',
        'vendor_doc_number','dop','stock_id','product_type_id','photos','tags','product_cost','unit_cost','paper_cart','published','approved','warehouse_id','supplier_id',
        'productcondition_id','custom_1','custom_2','custom_3','custom_4','custom_5','custom_6','custom_7','custom_8','custom_9','custom_10','msrp','min_qty','cost_code',
        'featured','partners'
    ];

    protected $with = ['product_translations', 'taxes'];

    public function getTranslation($field = '', $lang = false) {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations() {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks() {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes() {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product() {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids() {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function productcondition() {
        return $this->belongsTo(Productcondition::class);
    }
    public function productType() {
        return $this->belongsTo(ProductType::class);
    }

    public function getalltagsname(){
      $allTagArr = array();
      foreach (App\Brand::all() as $brand){
        $allTagArr[$brand->name] = $brand->name;
      }

      foreach (\App\SiteOptions::where('option_name', '=', 'model')->get() as $model){
        $allTagArr[$model->option_value] = $model->option_value;
      }
      foreach (App\Category::all() as $Category){
        $allTagArr[$Category->name] = $Category->name;
      }
      foreach (\App\Tag::all() as $tag){
        $allTagArr[$tag->tags] = $tag->tags;
      }

      return $allTagArr;
    }

}
