<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $domains = DB::table('domains')
            ->distinct()
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->select('domains.*', 'domain_checks.status_code')
            ->get();

        if (view()->exists('domains')) {
            return view('domains', ['domains' => $domains]);
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate(['domain' => 'required|url|max:255']);

            $url = $request->input('domain');
            $domainParts = parse_url($url);

            $domainName = $domainParts['scheme'] . '://' . $domainParts['host'];
            $domain = DB::table('domains')->select('id')->where('name', $domainName)->first();

            if (!$domain) {
                $domainId = DB::table('domains')->insertGetId([
                    'name'       => $domainName,
                    'created_at' => Carbon::now('Europe/Moscow'),
                    'updated_at' => Carbon::now('Europe/Moscow'),
                ]);

                if ($domainId) {
                    flash('Url was added')->success();

                    return redirect()->route('domains.show', $domainId);
                }
            }

            flash('Url already exists');
            return redirect()->route('domains.show', $domain->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        if ($id) {
            $domain = DB::table('domains')->select()->where('id', $id)->first();

            $domainChecks = DB::table('domain_checks')->select()->where('domain_id', $id)->get();

            if (view()->exists('domain') && $domain) {
                return view('domain', ['domain' => $domain, 'domainChecks' => $domainChecks ?? []]);
            }
        }

        abort(404);
    }
}
