<?php

namespace App\Http\Controllers;


use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EpkController extends Controller
{

    public function badAji()
    {
        $src = request()->query('src', null);
        $ref = request()->query('ref', null);

        // Assuming you have a PageVisit model
        PageVisit::create([
            'src' => $src,
            'ref' => $ref,
            'page' => 'bad-aji-epk',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);


        return view('epks.bad-aji-epk');
    }

}
