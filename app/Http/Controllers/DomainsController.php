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
//     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $domains = DB::table('domains')->select()->get();

        if (view()->exists('domains')) {
            return view('domains', ['domains' => $domains]);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate(['domain' => 'required|url']);

            $url = $request->input('domain');
            $domain_parts = parse_url($url);

            $domainName = $domain_parts['scheme'] . '://' . $domain_parts['host'];
            $domains = DB::table('domains')->select('id')->where('name', $domainName)->first();
            $domain = collect($domains)->toArray();

            if (!$domain) {
                $domain_id = DB::table('domains')->insertGetId([
                    'name'       => $domainName,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if ($domain_id) {
                    flash('Url was added')->success();

                    return redirect()->route('domains.show', $domain_id);
                }
            }

            flash('Url already exists');
            return redirect()->route('domains.show', $domain['id']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
//     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id) {
            $domain = DB::table('domains')->select()->where('id', $id)->first();

            if (view()->exists('domain') && $domain) {
                return view('domain', ['domain' => $domain]);
            }
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
