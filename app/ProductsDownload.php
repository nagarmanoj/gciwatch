<?php

namespace App;

use App\ProductType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsDownload implements WithHeadings
{
  protected $type;
  function __construct($type) {
      $this->type = $type;
  }

    public function headings(): array
    {
      $ProTyprData = ProductType::where('listing_type',$this->type)->first();
              $proHeading = array(
          'Product Type',
          'Current stock ID',
          'Product name',
          'Condition',
          'Brand',
          'Model Number',
          'Serial',
          'paper_cart',
          'Category',
          'size',
          'metal',
          'weight',
          'partners',
          'Warehouse',
          'unit',
          'Vendor Doc Number',
          'Supplier',
          'dop',
          'tags',
          'Product Cost',
          'Cost Code',
          'Sale Price',
          'MSRP',
          'Quantity',
          'Gallery Images',
          'Thumbnail image',
          'description',
          'external_link',
          'google_link',
          // 'added_by',
          'published',
          // 'approved',
          'featured',
          // 'file_name',
          // 'file_path',
        );

        if($ProTyprData != ""){
        for ($i=1; $i < 11 ; $i++) {
          $proKEy = 'custom_'.$i;
          $proCustom = $ProTyprData->$proKEy;
          if($proCustom != ""){
            $proHeading[] = $proCustom;
          }
        }
        }
        // $proHeading[] ='created_at';
        // $proHeading[] ='updated_at';

        return $proHeading;
    }

}
