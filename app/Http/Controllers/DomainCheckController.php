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

            $domain_check_id = 0;
            if ($domain) {
                $response = Http::get($domain->name);
                $html = $response->body();

                $document = new Document($html);

                $h1_element = $document->first('h1');
                if (isset($h1_element)) {
                    $h1 = $h1_element->text();
                }

                $meta_keywords_element = $document->first('meta[name=keywords]');
                if (isset($meta_keywords_element)) {
                    $keywords = $meta_keywords_element->getAttribute('content');
                }

                $meta_description_element = $document->first('meta[name=description]');
                if (isset($meta_description_element)) {
                    $description = $meta_description_element->getAttribute('content');
                }

                $domain_check_id = DB::table('domain_checks')->insertGetId([
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

            if ($domain_check_id) {
                flash('Website has been checked!');
                return redirect()->route('domains.show', $domain->id);
            }

            flash('Website has not been checked!');
            return redirect()->route('domains.show', $domain->id);
        }

        abort(404);
    }
}
