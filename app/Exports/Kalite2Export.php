namespace App\Exports;

use App\Models\Kalite2;
use Maatwebsite\Excel\Concerns\FromCollection;

class Kalite2Export implements FromCollection
{
    public function collection()
    {
        return Kalite2::all();
    }
}
