<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\ListMenu;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\UserResource;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $currentRole = (\Auth::check()) ? \Auth::user()->role : null;

        $lists = ListMenu::where('is_mother',1)->where('module','Executive')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $executive[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Reference')->where('group','Menu')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $reference3[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Reference')->where('group','List')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $reference1[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Reference')->where('group','Dropdowns')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $reference2[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Operation')->where('group','Menu')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $operation[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Operation')->where('group','Menu1')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $operation1[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Operation')->where('group','Menu2')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $operation2[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }

        $lists = ListMenu::where('is_mother',1)->where('module','Operation')->where('group','Menu3')->where('is_active',1)->orderBy('order','ASC')->get();
        foreach($lists as $list){
            $submenus = [];
            if($list['has_child']){
                $subs = ListMenu::where('is_active',1)->where('group',$list['name'])->get();
                foreach($subs as $menu){
                    $submenus[] = $menu;
                }
            }
            $operation3[] = [
                'main' => $list,
                'submenus' => $submenus
            ];
        }
       
        return [
            ...parent::share($request),
            'user' => (\Auth::check()) ? new UserResource(User::with('profile')->where('id',\Auth::user()->id)->first()) : '',
            'flash' => [
                'data' => session('data'),
                'message' => session('message'),
                'info' => session('info'),
                'status' => session('status'),
                'type' => session('type')
            ],
            'menus' => [
                'executive' => $executive,
                'reference3' => $reference3,
                'reference1' => $reference1,
                'operation' => $operation,
                'operation1' => $operation1,
                'operation2' => $operation2,
                'operation3' => $operation3
            ]
        ];
    }
}