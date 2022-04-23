<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CustomAuthController;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Hash;
use Session;

class EmployeeController extends Controller
{
    public function index()
    {
        $data = array();
        if(Session::has('loginId')){
            $data=User::where('id', '=', Session::get('loginId'))->first();
        }
        $employee = Employee::all();
        return view('employee.index',compact('data','employee'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $data = array();
        if(Session::has('loginId')){
            $data=User::where('id', '=', Session::get('loginId'))->first();
        }
        return view('employee.create',compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            
        ]);
        $employee = new Employee;
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->number = $request->input('number');
        $employee->department = $request->input('department');
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('storage/images/', $filename);
            $employee->image = $filename;
        }
        $employee->save();
        return redirect()->back()->with('status','Employee Details Added Successfully');
        
    }

    public function edit($id)
    {
        if(!Session()->has('loginId')){
            return redirect('login')->with('fail', 'You have to Login First');
        }
        $data = array();
        if(Session::has('loginId')){
            $data=User::where('id', '=', Session::get('loginId'))->first();
        }
        $employee = Employee::find($id);
        return view('employee.edit', compact('employee','data'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->department=$request->department;
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->number = $request->input('number');
        $employee->department = $request->input('department');
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $employee['image'] = "$profileImage";
        }else{
            unset($employee['image']);
        }

        $employee->update();
        return redirect()->back()->with('status','Employee Details Updated Successfully');
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        $destination = 'uploads/employees/'.$employee->image;
        if(File::exists($destination))
        {
            File::delete($destination);
        }
        $employee->delete();
        return redirect()->back()->with('status','Employee Details Deleted Successfully');
    }

    // public function destroyall()
    // {
    //     $employee=Employee::truncate();
    //     // $employee->destroyall();
    //     return redirect()->back()->with('status','All Employees Data Deleted Successfully');
    // }
}