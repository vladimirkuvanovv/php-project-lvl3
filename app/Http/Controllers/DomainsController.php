<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->select()
            ->orderBy('id')
            ->get();

        $domainsStatusCodes = DB::table('domain_checks')
            ->distinct()
            ->select('domain_id', 'status_code')
            ->get();
        
        $lastChecks = collect($domainsStatusCodes)
            ->mapWithKeys(function ($domainsStatusCode) {
                return [$domainsStatusCode->domain_id => $domainsStatusCode->status_code];
            })
            ->toArray();

        return view('domains.index', ['domains' => $domains, 'lastChecks' => $lastChecks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate(['domain' => 'required|url|max:255']);

        $url = $request->input('domain');
        $domainParts = parse_url($url);

        $domainName = $domainParts['scheme'] . '://' . $domainParts['host'];
        $domain = DB::table('domains')->select('id')->where('name', $domainName)->first();

        if ($domain) {
            flash('Url already exists');

            return redirect()->route('domains.show', $domain->id);
        }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $domain = DB::table('domains')->select()->where('id', $id)->first();
        abort_unless((bool)$domain, 404);

        $domainChecks = DB::table('domain_checks')
            ->select()
            ->where('domain_id', $id)
            ->orderBy('domain_id')
            ->get();

        return view('domains.show', ['domain' => $domain, 'domainChecks' => $domainChecks ?? []]);
    }
}
