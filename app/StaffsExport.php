<?php
namespace App;
use App\Staff;
use App\Transfer;
use App\TransferItem;
use App\Product;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
class StaffsExport implements FromCollection, WithMapping, WithHeadings
{
    protected $ids;
    function __construct($ids) {
        $this->ids = $ids;
    }
    public function collection()
    {
       $Staff=Staff::select('staff.*','users.*','roles.name as roleName')
        ->leftJoin('users','users.id','=','staff.user_id')
        ->leftJoin('roles','roles.id','=','staff.role_id')
        ->whereIn('staff.id',$this->ids)
        ->get();
        return $Staff;
    }
    public function headings(): array
    {
      return [

            'Name',
            'Email',
            'Password',
            'Company',
            'Phone',
            'Gender',
            'Group',
            'Avatar',
            'created',
            'Last Login',
            'Status',
        ];

    }



    /**

    * @var Staff $staff

    */

    public function map($staff): array

    {

        // dd($staff);exit;

        if($staff->status == 1)

        {

           $statu='Active';

        }
        else

        {

           $statu='Deactive';

        }


        return [
            $staff->name,
            $staff->email,
            $staff->password,
            $staff->company,
            $staff->phone,
            $staff->gender,
            $staff->roleName,
            uploaded_asset($staff->avatar),
            $staff->created_at,
            $staff->updated_at,
            $statu,

        ];

    }

}
