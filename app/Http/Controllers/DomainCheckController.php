<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function store(Request $request, $id)
    {
        //
        if ($request->isMethod('post')) {
            $domain = DB::table('domains')->select()->where('id', $id)->first();

            $domain_check_id = 0;
            if ($domain) {
                $response = Http::get($domain['name']);

                $domain_check_id = DB::table('domain_checks')->insertGetId([
                    'domain_id'   => $id,
                    'status_code' => $response->status(),
                    'created_at'  => Carbon::now('Europe/Moscow'),
                    'updated_at'  => Carbon::now('Europe/Moscow'),
                ]);

                DB::table('domains')->where('id', $id)->update(['updated_at' => Carbon::now('Europe/Moscow')]);
            }

            if ($domain_check_id) {
                flash('Website has been checked!');
                return redirect()->route('domains.show', $domain['id']);
            }

            flash('Website has not been checked!');
            return redirect()->route('domains.show', $domain['id']);
        }

        abort(404);
    }
}
