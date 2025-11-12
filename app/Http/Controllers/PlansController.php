<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PlansController extends Controller
{

    public function index(Request $request)
    {
        $sql = "SELECT pla_id as id,pla_name as name ,case when (now() < coalesce(pla_enddate,'2099-12-31')) then 'ALTA' else 'BAJA' end as status FROM pbs_rating.ra_plans";
        $results = DB::select($sql);
        return response()->json($results, 200);
    }
}
