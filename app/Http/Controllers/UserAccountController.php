<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\User;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManagerStatic as Image;
use League\Flysystem\Exception;

class UserAccountController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        return view('users_accounts.edit', compact('user')); //pass user data to view

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get user specified by id

        //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6'
        ]);
        $hasher = app('hash');
        if ($hasher->check($request['password'], $user->password))
        {
            $user->name = $request['name'];
            $user->email = $request['email'];
            if($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                try
                {
                    $image = Image::make($file);
                    $path = 'storage/avatars/' . time() . $file->getClientOriginalName();
                    //deleting old avatar (if it is not default:
                    if (!$this->isAvatarDefault($user->avatar))
                        Storage::delete(str_replace('storage', 'public', $user->avatar));
                    $size = $this->getNewImgSize($image->getWidth(), $image->getHeight());
                    $image->resize($size['width'], $size['height'])->save($path);
                    $user->avatar = $path;
                    flash('Your avatar was successfully updated!');
                }
                catch (NotReadableException $ex)
                {
                    flash('File you uploaded is not an image!')->error();
                }
            }
            $user->save();
            flash($user->name. ', your account was updated!');
            return redirect()->route('home');
        }
        else
        {
            flash('Wrong password! Can\'t save changes.')->error();
            return redirect()->back();
        }
    }

    public function updateAvatar(Request $request, $id)
    {
        $user = User::findOrFail($id); //Get user specified by id
        if($request->hasFile('file')) {
            $file = $request->file('file');
            try
            {
                $image = Image::make($file);
                $path = 'storage/avatars/' . time() . $file->getClientOriginalName();
                //deleting old avatar (if it is not default:
                if (!$this->isAvatarDefault($user->avatar))
                    Storage::delete(str_replace('storage', 'public', $user->avatar));
                $size = $this->getNewImgSize($image->getWidth(), $image->getHeight());
                $image->resize($size['width'], $size['height'])->save($path);
                $user->avatar = $path;
                flash('Your avatar was successfully updated!');
                $user->save();
                flash($user->name. ', your avatar was updated!');
                $response = array(
                    'success' => 200,
                    'name'    => $file->getClientOriginalName()
                );
                return json_encode($response);
            }
            catch (NotReadableException $ex)
            {
                flash('File you uploaded is not an image!')->error();
                $response = array(
                    'error' => 400
                );
                return json_encode($response);
            }
        }
        $response = array(
            'error' => 400
        );
        return json_encode($response);
    }

    /**
     * Chechs if current avatar path is default.
     *
     * @param  string $avatar
     * @return bool
     */
    private function isAvatarDefault($avatar)
    {
        return strcmp($avatar, 'storage/avatars/user.png') == 0;
    }

    private function getNewImgSize(int $widthInit, int $heightInit, int $widthMax = 100, $heightMax = 150 ) : array
    {
    $proportionIndex = $widthInit/$heightInit;
    if ($proportionIndex >= 1)
    {
        $widthNew = $widthMax;
        $heightNew = ($widthNew * $heightInit) / $widthInit;
    }
    else
    {
        $heightNew = $heightMax;
        $widthNew = ($heightNew *$widthInit) / $heightInit;
    }
    $size['width'] = $widthNew;
    $size['height'] = $heightNew;
    return $size;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id) {
        $user = User::findOrFail($id); //Get user specified by id

        //Validate name, email and password fields
        $this->validate($request, [
            'prev_password'=>'required|min:6',
            'new_password'=>'required|min:6|confirmed'
        ]);
        $hasher = app('hash');
        if ($hasher->check($request['prev_password'], $user->password))
        {
            $user->setPasswordAttribute($request['new_password']);
            $user->save();
            flash($user->name. ', your password was updated!');
            return redirect()->route('home');
        }
        else
        {
            flash('Wrong password! Can\'t save changes.')->error();
            return redirect()->back();
        }
    }


}
