<?php

namespace App\Http\Controllers;

use App\models\Contact;
use Illuminate\Http\Request;
use App\User;
use App\models\ContactType;

class ContactsController extends Controller
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
    public function create(Request $request)
    {
        $user_id = $request['user_id'];
        $user = User::findOrFail($user_id);
        $contact_types = ContactType::all()->pluck('contact_type', 'id');
        return view('contacts.create', compact('user', 'contact_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name, email and password fields
        $this->validate($request, [
            'user_id'=>'required',
            'value' => 'required'
        ]);
        if($request['new_contact_type'] != '')
        {
            //creating a new contact type:
            $contact_type = new ContactType();
            $contact_type->contact_type = $request['new_contact_type'];
            $contact_type->save();
            //creating a new contact of this type:
            $contact = new Contact();
            $contact->user_id = $request['user_id'];
            $contact->type_id = $contact_type->id;
            $contact->value = $request['value'];
            $contact->save();
        }
        else
            $contact = Contact::create($request->only('user_id', 'type_id', 'value'));

        flash('Contact '. $contact->value. ' was added!');
//        return redirect()->route('users.index', ['filter' => 'all']);
        return redirect()->back();
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
       $contact = Contact::findOrFail($id);
       $contact_types = ContactType::all()->pluck('contact_type', 'id');
       return view('contacts.edit', compact('contact', 'contact_types'));
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
        $contact = Contact::findOrFail($id);
        $this->validate($request, [
            'type_id'=>'required',
            'value' => 'required'
        ]);
        $contact->fill($request->all())->save();
        flash('Contact '. $contact->value. ' was updated!');
        return redirect()->route('users.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        flash('Contact '. $contact->value. ' was deleted!');
        return redirect()->route('users.index', ['filter' => 'all']);
    }
}
