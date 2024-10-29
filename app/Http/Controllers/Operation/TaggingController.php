<?php

namespace App\Http\Controllers\Operation;

use App\Traits\HandlesTransaction;
use App\Services\DropdownClass;
use App\Services\Operation\Tagging\ViewClass;
use App\Services\Operation\Tagging\SaveClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaggingController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown, SaveClass $save, ViewClass $view){
        $this->dropdown = $dropdown;
        $this->save = $save;
        $this->view = $view;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            default :
            return inertia('Modules/Operation/Taggings/Index');
        }
    }
}
