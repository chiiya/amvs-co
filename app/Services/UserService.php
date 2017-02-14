<?php
/**
 * Created by PhpStorm.
 * User: allaire
 * Date: 12.02.17
 * Time: 16:00
 */

namespace App\Services;

use App\User;
use Illuminate\Http\Request;

class UserService
{

    protected $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param   $id
     * @return  \App\User
     */
    public function get($id, $contests = false)
    {
        try {
            $query = $this->user->where('id', $id);
            if ($contests) $query = $query->with('contests');
            return $query->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function getByName($name)
    {
        try {
            return $this->user->where('name', $name)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param   Array $input
     * @return  \App\User
     */
    public function store($input)
    {
        return $this->user->create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
    }

    /**
     * @param   \App\Http\Requests\UserRequest $request
     * @param   $id
     * @return  mixed
     */
    public function update($request, $id)
    {
        $user = $this->get($id);
        if (!$user) return 404;
        // Check if user is authorized to perform the action
        if (!$request->user()->can('update', $user)) return 401;

        // If a new password has been provided, hash it and update user.
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // If an avatar has been uploaded, store it and link it to user.
        // Rename it to username_uniqueId.extension, e.g. allaire_3245325234.png
        if ($request->hasFile('avatar')) {
            $prefix = strtolower($user->name) . '_';
            $filename = uniqid($prefix) . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('images'), $filename);
            $oldavatar = public_path() . $user->avatar;
            if (file_exists($oldavatar)) unlink($oldavatar);
            $user->avatar = '/images/' . $filename;
        }

        // Update all other properties
        $input = $request->except(['avatar', 'password']);
        $user->fill($input);

        // Save new user object to DB
        $user->save();

        return $user;
    }

    /**
     * @param   $id
     * @return  bool
     */
    public function destroy($request, $id)
    {
        $user = $this->get($id);
        if (!$user) return 404;
        // Check if user is authorized to perform the action
        if (!$request->user()->can('delete', $user)) return 401;

        $user->delete();
        return true;
    }

    /**
     * @param   $id
     * @return  array
     */
    public function getContests($id)
    {
        $contests = [
            'creator' => [],
            'admin' => [],
            'judge' => [],
            'participant' => []
        ];
        $user = $this->get($id, true);
        if (!$user) return false;
        foreach ($user->contests as $contest) {
            if ($contest->pivot->role === 1) $contests['creator'][] = $contest;
            elseif ($contest->pivot->role === 2) $contests['admin'][] = $contest;
            elseif ($contest->pivot->role === 3) $contests['judge'][] = $contest;
            elseif ($contest->pivot->role === 4) $contests['participant'][] = $contest;
        }
        return $contests;
    }
}