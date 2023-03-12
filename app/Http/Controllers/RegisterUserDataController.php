<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;

class RegisterUserDataController extends Controller
{
    public function registerUserAccountData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('USERS')->select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        if ($request->get('pool_no') != '') {
                            $instance->where('pool_no', $request->get('pool_no'));
                        }
                        if ($request->get('amount') != '') {
                            $instance->where('amount', $request->get('amount'));
                        }
                        if ($request->get('name') != '') {
                            $instance->where('name', $request->get('name'));
                        }
                        if ($request->get('email') != '') {
                            $instance->where('email', $request->get('email'));
                        }
                        if ($request->get('search') != '') {
                            $instance->where('id', $request->get('search'));
                        }
                    })

                    ->make(true);
        }
        
        return view('registerUserDataView');
    }
}
