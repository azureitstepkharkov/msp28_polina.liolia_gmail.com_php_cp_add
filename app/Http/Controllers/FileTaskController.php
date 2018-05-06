<?php

namespace App\Http\Controllers;

use Faker\Provider\File;
use Illuminate\Http\Request;
use App\models\FileTask;
use Illuminate\Support\Facades\Storage;

class FileTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->hasFile('file')) {
            $file = $request->file('file');
            $clientOriginalName = $file->getClientOriginalName();
            Storage::putFileAs('public', $file, $clientOriginalName);
            $path = 'storage/' . $clientOriginalName;
            $task_id = $request->task_id;
            $user_id = $request->author_id;
            $file = FileTask::create(['task_id'=>$task_id, 'user_id'=>$user_id, 'path'=>$path]);
            $file->save();
            flash('File ' . $clientOriginalName . ' was successfully uploaded!');
        }
        else
            flash('No file was selected!')->important();
        return back();
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
       $file = FileTask::findOrFail($id);
       Storage::delete($file->path); //delete from disk
       $file->delete(); //delete from db
       flash('File was removed!');
       return back();
    }
}
