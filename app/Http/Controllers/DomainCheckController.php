<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{
    private const MAX_LENGTH_STRING = 60;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function store(Request $request, $id)
    {
        $domain = DB::table('domains')->find($id);
        abort_unless((bool)$domain, 404);

        try {
            $response = Http::get($domain->name);
            $html = $response->body();

            $document = new Document($html);
            $h1 = optional($document->first('h1'))->text();
            $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');

            if (
                mb_strlen($h1) > self::MAX_LENGTH_STRING ||
                mb_strlen($keywords) > self::MAX_LENGTH_STRING ||
                mb_strlen($description) > self::MAX_LENGTH_STRING
            ) {
                $h1 = mb_substr($h1, 0, self::MAX_LENGTH_STRING);
                $keywords = mb_substr($keywords, 0, self::MAX_LENGTH_STRING);
                $description = mb_substr($description, 0, self::MAX_LENGTH_STRING);
            }

            DB::table('domain_checks')->insertGetId([
                'domain_id'   => $id,
                'status_code' => $response->status(),
                'keywords'    => $keywords,
                'description' => $description,
                'h1'          => $h1,
                'created_at'  => Carbon::now('Europe/Moscow'),
                'updated_at'  => Carbon::now('Europe/Moscow'),
            ]);

            DB::table('domains')->where('id', $id)->update(['updated_at' => Carbon::now('Europe/Moscow')]);

            flash('Website has been checked!');
        } catch (RequestException $e) {
            flash('Website has not been checked!')->error();
        }

        return redirect()->route('domains.show', $domain->id);
    }
}
