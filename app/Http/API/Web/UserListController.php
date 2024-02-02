<?php

namespace App\Http\API\Web;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;

class UserListController extends Controller
{
    public function __invoke()
    {
        $search = request()->get('query');
        $id     = request()->get('id');
        $strict = request()->get('strict');
        $limit  = request()->get('limit') ?: 15;

        $users = User::query()
            ->select('id', 'first_name', 'last_name', 'phone')
            ->when($strict, fn ($user) => $user->where('id', '=', $id))
            ->when($search, function ($user) use ($search) {
                $user->where(function ($user) use ($search) {
                    $user->whereRaw("concat(first_name, ' ', last_name) like ?", '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                    ;
                });
            })
            ->limit($limit)
            ->orderByRaw('IF(id = ?, 0,1)', [$id])
            ->get()
            ->transform(function ($item, $key) {
                return [
                    'value'      => $item->id,
                    'label'      => "{$item->first_name} {$item->last_name} ({$item->phone})",
                    'first_name' => $item->first_name,
                    'last_name'  => $item->last_name,
                ];
            });
        return response()->json($users);
    }
}
