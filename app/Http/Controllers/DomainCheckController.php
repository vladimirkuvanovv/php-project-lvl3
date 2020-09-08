<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
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
        if ($request->isMethod('post')) {
            $domain = DB::table('domains')->select()->where('id', $id)->first();

            $domainCheckId = 0;
            if ($domain) {
                $response = Http::get($domain->name);
                $html = $response->body();

                $document = new Document($html);

                $h1Element = $document->first('h1');
                if (isset($h1Element)) {
                    $h1 = $h1Element->text();
                }

                $metaKeywordsElement = $document->first('meta[name=keywords]');
                if (isset($metaKeywordsElement)) {
                    $keywords = $metaKeywordsElement->getAttribute('content');
                }

                $metaDescriptionElement = $document->first('meta[name=description]');
                if (isset($metaDescriptionElement)) {
                    $description = $metaDescriptionElement->getAttribute('content');
                }

                $domainCheckId = DB::table('domain_checks')->insertGetId([
                    'domain_id'   => $id,
                    'status_code' => $response->status(),
                    'keywords'    => $keywords ?? null,
                    'description' => $description ?? null,
                    'h1'          => $h1 ?? null,
                    'created_at'  => Carbon::now('Europe/Moscow'),
                    'updated_at'  => Carbon::now('Europe/Moscow'),
                ]);

                DB::table('domains')->where('id', $id)->update(['updated_at' => Carbon::now('Europe/Moscow')]);
            }

            if ($domainCheckId) {
                flash('Website has been checked!');
                return redirect()->route('domains.show', $domain->id);
            }

            flash('Website has not been checked!');
            return redirect()->route('domains.show', $domain->id);
        }

        abort(404);
    }
}
