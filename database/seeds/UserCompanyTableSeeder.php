<?php
use App\Http\Models\Company;
use App\Http\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rhumsaa\Uuid\Uuid;

class UserCompanyTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            $id = Uuid::uuid4()->toString();
            $user = User::where('role_id', '=', '2')->orderBy(DB::raw('RAND()'))->take(1)->first();
            $company = Company::orderBy(DB::raw('RAND()'))->take(1)->first();
            $mytime = Carbon\Carbon::now();
            DB::table('users_companies')->insert(['id' => $id, 'user_id' => $user->id, 'company_id' => $company->id, 'created_at' => $mytime, 'updated_at' => $mytime]);
        }
    }
}
