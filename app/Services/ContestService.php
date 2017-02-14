<?php
/**
 * Created by PhpStorm.
 * User: allaire
 * Date: 13.02.17
 * Time: 01:28
 */

namespace App\Services;


use App\Contest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContestService
{
    protected $contest;

    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    public function index()
    {
        return $this->contest->all();
    }

    public function get($id, $users=false)
    {
        try {
            $query = $this->contest->where('id', $id);
            if ($users) $query = $query->with('users');
            return $query->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function destroy($request, $id)
    {
        $contest = $this->get($id);
        if (!$contest) return 404;
        if (!$request->user()->can('delete', $contest)) return 401;

        $contest->delete();
        return true;
    }

    public function getUsers($id)
    {
        $users = [
            'creator' => [],
            'admins' => [],
            'judges' => [],
            'participants' => []
        ];
        $contest = $this->get($id, true);
        if (!$contest) return false;
        foreach ($contest->users as $user) {
            if ($user->pivot->role === 1) $users['creator'][] = $user;
            elseif ($user->pivot->role === 2) $users['admins'][] = $user;
            elseif ($user->pivot->role === 3) $users['judges'][] = $user;
            elseif ($user->pivot->role === 4) $users['participants'][] = $user;
        }
        return $users;
    }

    public function storeUser(UserService $userService, $request)
    {
        $input = $input = $request->json()->all();
        $contest = $this->get($input['contest_id'], true);
        $user = $userService->get($input['user_id']);
        if (!$contest || !$user) return 404;
        if (!$request->user()->can('update', $contest)) return 401;

        $contest->attach($user->id, ['role' => $input['role']]);

        return $contest;
    }

    public function destroyUser($request, $id, $userId, $role)
    {
        $contest = $this->get($id);
        if (!$contest) return 404;
        if ($role === 0 || !$request->user()->can('update', $contest)) return 401;
        $contest->users()->newPivotStatementForId($userId)->where('role', $role)->delete();
        return true;
    }
}