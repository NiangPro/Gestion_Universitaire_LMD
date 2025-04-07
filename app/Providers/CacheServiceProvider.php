namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class CacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Cache des données fréquemment utilisées
        Cache::remember('campus_data', 3600, function () {
            return [
                'academic_years' => AcademicYear::all(),
                'payment_types' => ['inscription', 'mensualite', 'complement'],
                'payment_modes' => ['espece', 'cheque', 'virement', 'mobile_money']
            ];
        });
    }
} 