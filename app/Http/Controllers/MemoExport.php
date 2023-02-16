<?php

namespace App;

use App\Memo;
use App\Product;
use App\MemoDetail;
use App\RetailReseller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class MemoExport implements FromCollection, WithMapping, WithHeadings
{
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    public function collection()
    {
        if($id){
            $memoData = Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name')
            ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
            ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
            ->join('products', 'memo_details.product_id', '=', 'products.id')
            ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
            ->selectRaw('GROUP_CONCAT(model) as model_numbers')
            ->groupBy('memo_details.memo_id')
            ->where('id',$id)
            ->get();
            return $memoData;
        }
        else{
        // return Memo::all();
        $memoData = Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->join('products', 'memo_details.product_id', '=', 'products.id')
        ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
        ->selectRaw('GROUP_CONCAT(model) as model_numbers')
        ->groupBy('memo_details.memo_id')
        ->get();
        return $memoData;
        // dd($memoData);
    }
}

    public function headings(): array
    {
        return [
            'memo_number',
            'company',
            'customer_name',
            'reference',
            'stocks',
            'model_numbers',
            'payment',
            'sub_total',
            'tracking',
            'memo_status',
            'due_date',
            'date',
        ];
    }

    /**
    * @var Memo $memo
    */
    public function map($memoData): array
    {
        return [
            $memoData->memo_number,
            $memoData->company,
            $memoData->customer_name,
            $memoData->reference,
            $memoData->stocks,
            $memoData->model_numbers,
            $memoData->payment,
            $memoData->sub_total,
            $memoData->tracking,
            $memoData->memo_status,
            $memoData->due_date,
            $memoData->date,
            // $qty,
        ];
    }
}
