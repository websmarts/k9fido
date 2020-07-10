<?php

namespace App\Http\Controllers;

use App\Legacy\Staff\User as Staff;
use App\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{

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
        $staff = Staff::all();

        return view('admin.staff.index')->with('staff', $staff);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Staff::find($id);

        $fidoUser = User::where('name',$user->name)->first();
        if($fidoUser){
            $user->email = $fidoUser->email;
            $user->user_id = $fidoUser->id;

        } else {
            $user->email = '';
        }
       

        return view('admin.staff.edit')->with('user',$user);
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
        
        $data = $request->all();
        $staff = Staff::find($id);

        $fidoUser = User::find($data['user_id']);

        if ( !empty($data['set_password']) ){
            // password is being updated
            $staff['pass'] = md5($data['set_password']);// ecat password update
            $fidoUser->password = bcrypt($data['set_password']);// fido user password update
        }

        unset($data['set_password']);

        // Update eCat user table
        $staff->update($data);


        // Update fido user table
        $fidoUser->email = $data['email'];
        $fidoUser->name = $data['name'];
        $fidoUser->save();



        return redirect(route('staff.index'));


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
