<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Visitor, App\Visit, DB, Datatables;

class VisitorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitors = Visitor::orderBy('visits_count', 'DESC')->withCount('visits', 'favorites')->paginate(25);
        return view('visitors.index', compact(['visitors']));
    }

    public function indexData()
    {
        return Datatables::of(Visitor::orderBy('visits_count', 'DESC')->withCount('visits', 'favorites')->get())
            ->addColumn('operations','<a class="delete btn btn-danger btn-sm" href="javascript:;"><i class="fa fa-trash"></i> </a>')
            ->editColumn('notification_token', '@if($notification_token != null)
                                                     <td>Var</td> 
                                                @endif
                                                @if ($notification_token == null)
                                                    <td>Yok</td>
                                                @endif')
            ->make(true);

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $visitorsCount = Visitor::count();
        $visitorsDaily = Visitor::groupBy('date')
            ->orderBy('date')
            ->get(array(
                DB::raw('DATE(`created_at`) AS `date`'),
                DB::raw('COUNT(*) as `value`')
            ));
        $visitorDevices = DB::table('visitors')
            ->select('via', DB::raw('count(*) as total'))
            ->groupBy('via')
            ->orderBy('total', 'desc')
            ->get();

        return view('visitors.statistics', compact(['visitorsCount', 'visitorDevices', 'visitorsDaily']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visitor = Visitor::find($id);
        $visitor->visits()->delete();
        $visitor->delete();
        return 'Success';
    }

}
