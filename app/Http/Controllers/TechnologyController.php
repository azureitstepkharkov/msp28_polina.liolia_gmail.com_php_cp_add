<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Technology;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $technologies = Technology::all(); //get all technologies

        return view('technologies.index')->with('technologies', $technologies);
    }

    public function indexApi()
    {
        $technologies = Technology::all(); //get all technologies
        return response()->json($technologies, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $projects = Project::all();//Get all projects TODO: Project model
        return view('technologies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and project_id field
        $this->validate($request, [
                'name'=>'required|max:150',
                'description' =>'required|max:400'
            ]
        );

        $technology = new Technology();
        $technology->name = $request['name'];
        $technology->description = $request['description'];
        $technology->save();

        flash('Technology '. $technology->name.' was added!');
        return redirect()->route('technologies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('technologies');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $technology = Technology::findOrFail($id);

        return view('technologies.edit', compact('technology'));
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
        $technology = Technology::findOrFail($id);
        $this->validate($request, [
                'name'=>'required|max:150',
                'description' =>'required|max:400',
            ]
        );
        $input = $request->only(['name', 'description']);
        $technology->fill($input)->save();

        flash('Technology '. $technology->name.' was updated!');
        return redirect()->route('technologies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $technology = Technology::findOrFail($id);
        $technology->delete();

        flash('Technology ' . $technology->name . ' was deleted!');
        return redirect()->route('technologies.index');
    }
}
