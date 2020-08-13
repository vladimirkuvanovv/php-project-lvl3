<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
            $url = $request->input('domain');
            $domain_parts = parse_url($url);

            $domainName = $domain_parts['scheme'] . '://' . $domain_parts['host'];
            $domain = DB::table('domains')->select('name')->where('name', $domainName)->get();
            dump($domain);


            if (!$domain) {
                dump(1);
                $domain_id = DB::table('domains')->insertGetId(['name' => $domainName]);
                dump($domain_id);

                if ($domain_id) {
                    $domain = DB::table('domains')->select()->where('id', $domain_id)->get();
                    flash('Url was added')->success();

                    return redirect()->route('domains.show');
                }
            }

            flash('Url already exists');

            return redirect()->route('domains.show');
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
            $domain = DB::table('domains')->select()->where('id', $id)->get();

            //найти данные по id и прокинуть в шаблон
            if (view()->exists('domain') && $domain) {
                return view('domain', ['domain' => $domain]);
            }

            return view('domain');
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
