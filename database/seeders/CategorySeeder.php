<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    static $categories = [
        // [
        //     'job_type' => 'jobwall',
        //     'name_id' => 'paycheck',
        //     'name' => 'Paycheck',
        // ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'contracts',
            'name' => 'Contracts',
        ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'shits_working_hours',
            'name' => 'Shits/Working hours',
        ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'full_holidays',
            'name' => 'Full holidays',
        ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'leave',
            'name' => 'Leave',
        ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'convocations_visits',
            'name' => 'Convocations of health sureillance visits',
        ],
        [
            'job_type' => 'jobwall',
            'name_id' => 'welfare',
            'name' => 'Welfare',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'ccnl',
            'name' => 'CCNL',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'disciplinary_ode',
            'name' => 'Disciplinary Code / Ethical Code',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'cia',
            'name' => 'CIA',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'trade_union_agreements',
            'name' => 'Trade Union Agreements',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'regulations',
            'name' => 'Regulations',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'smartworking',
            'name' => 'Smartworking',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'Trade_union_communications',
            'name' => 'Trade Union Communications',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'health_safety_protocol',
            'name' => 'Health-Safety Protocol',
        ],
        [
            'job_type' => 'jobdrawer',
            'name_id' => 'privacy_notice',
            'name' => 'Privacy Notice',
        ],
    ];

    public function run()
    {
        foreach (self::$categories as $category) {
            DB::table('category')->insert([
                'job_type' => $category['job_type'],
                'name' => $category['name'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
