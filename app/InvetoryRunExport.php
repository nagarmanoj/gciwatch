<?php

namespace App;

use App\InventoryRun;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvetoryRunExport implements FromArray, WithMapping, WithHeadings
{
    protected $ids;
    function __construct($ids) {
        $this->ids = $ids;
    }
    public function array(): array
    {
        $InventoryRun = InventoryRun::select('inventory_runs.id','inventory_runs.listing_type','inventory_runs.missing','inventory_runs.duplicate','inventory_runs.extra','inventory_runs.extrakeyup','inventory_runs.created_at','product_types.product_type_name','users.name')
       ->leftJoin('users','users.id','=','inventory_runs.user')
       ->leftJoin('product_types','product_types.id','=','inventory_runs.listing_type')
       ->orderBy('inventory_runs.id', 'desc')
       ->whereIn('inventory_runs.id',$this->ids)
       ->get();
        // $InventoryRun =InventoryRun::findOrFail($this->ids);
        $InvmissNewArr = array();
        foreach ($InventoryRun as $InvRun) {
         $InvUser = $InvRun->name;
         $Invduplicate = $InvRun->duplicate;
         $Invmissing = $InvRun->missing;
         $Invextra = $InvRun->extra;
         $Invextrakeyup = $InvRun->extrakeyup;
         $created_at = $InvRun->created_at->format('d-m-Y');
         $totalExtra = $Invextra;
         if($totalExtra != ""){
           $totalExtra= $totalExtra.','.$Invextrakeyup;
         }else{
           $totalExtra = $Invextrakeyup;
         }
         $InvmissingArr = array();
          if($Invmissing != ""){
            $InvmissingArr = explode(',',$Invmissing);
          }
          $InvextraArr = array();
          if($totalExtra != ""){
            $InvextraArr = explode(',',$totalExtra);
          }
          $InvduplicateArr = array();
          if($Invduplicate != ""){
            $InvduplicateArr = explode(',',$Invduplicate);
          }

        $missingLen = count($InvmissingArr);
        $extraLen = count($InvextraArr);
        $duplicateLen = count($InvduplicateArr);
        $AllCountArr =array(
          $missingLen,
          $extraLen,
          $duplicateLen
        );
        $maxCount = max($AllCountArr);
        for ($i=0; $i < $maxCount; $i++) {
          $maxMissingVal = isset($InvmissingArr[$i]) ? $InvmissingArr[$i] : "";
          $InvextraVal = isset($InvextraArr[$i]) ? $InvextraArr[$i] : "";
          $InvduplicateVal = isset($InvduplicateArr[$i]) ? $InvduplicateArr[$i] : "";
          $xlDataArr = array(
            $InvUser,
            $maxMissingVal,
            $InvextraVal,
            $InvduplicateVal,
            $created_at
          );
          $InvmissNewArr[]=$xlDataArr;
        }


        }
        return $InvmissNewArr;
        // dd($InventoryRun);
    }

    public function headings(): array
    {
        return [
            'User',
            'Missing',
            'duplicate',
            'Extra',
            'Created at',
        ];
    }

    /**
    * @var InventoryRun $inventoryrun
    */
    public function map($inventoryrun): array
    {
      // print_r($inventoryrun);
      // exit;
        return [
           $inventoryrun[0],
           $inventoryrun[1],
           $inventoryrun[2],
           $inventoryrun[3],
           $inventoryrun[4]
        ];
    }
}
