<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Tsr;
use App\Models\User;
use App\Models\Configuration;
use App\Models\ListDropdown;
use App\Models\Laboratory;
use App\Models\TsrSample;
use App\Models\TsrAnalysis;
use App\Models\EulimsProduct;
use Illuminate\Http\Request;
use App\Http\Resources\TsrResource;
use App\Http\Resources\Laboratory\SampleResource;
use App\Services\Laboratory\DropdownClass;
use App\Http\Requests\UserRequest;
use App\Traits\HandlesTransaction;
use App\Http\Requests\InstallRequest;
use App\Http\Resources\Laboratory\VerificationResource;
use App\Http\Resources\ItemsResource;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\Http;

class WelcomeController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown){
        $this->dropdown = $dropdown;
    }

    public function items(){
        $data = EulimsProduct::with('entries.withdrawals.detail','entries.supplier')->where('discontinued',0)->where('producttype_id',1)->get();
        $items = ItemsResource::collection($data);
        return $items;
        foreach($items as $itemm){
            $itemArray = $itemm->toArray(request());
            $item = InventoryItem::create([
                'code' => 'R9-INV-'.str_pad((InventoryItem::count()+1), 5, '0', STR_PAD_LEFT),
                'old_code' => $itemArray['code'],
                'name' => ucwords(strtolower($itemArray['name'])),
                'unit_id' => $itemArray['unit_id'],
                'img' => 'avatar.jpg',
                'laboratory_id' => 14,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            if($item){
                foreach($itemArray['entries']->toArray(request()) as $entry){
                    $entries = $item->stocks()->create([
                        'code' => date('Ymdhis')+InventoryStock::count()+1,
                        'quantity' => $entry['quantity'],
                        'number' => ($entry['number']) ? ($entry['number'] == '') ? null : $entry['number'] : null,
                        'onhand' => $entry['onhand'],
                        'price' => $entry['price'],
                        'unit' => $entry['unit'],
                        'unit_id' => $itemArray['unit_id'],
                        'supplier' => $entry['supplier'],
                        'expired_at' => ($entry['expired_at']) ? $entry['expired_at'] : null,
                        'bought_at' => ($entry['created_at']) ? $entry['created_at'] : null,
                        'user_id' => 1
                    ]);

                    if($entry['onhand'] > 0){
                        $item->is_active = 1;
                        $item->save();
                    }

                    if($entries){
                        if(count($entry['withdrawals']->toArray(request())) > 0){
                            foreach($entry['withdrawals']->toArray(request()) as $withdrawal){
                                $with = $entries->withdrawals()->create([
                                    'quantity' => $withdrawal['quantity'],
                                    'created_at' => $withdrawal['created_at'], 
                                    'user_id' => 1
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function landing(){
        if(!\Auth::check()){
            return inertia('Auth/Login',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratories(),
                    'types' => $this->dropdown->laboratory_all(),
                    'roles' => $this->dropdown->roles(),
                ]
            ]);
        }else{
            return inertia('Modules/Laboratory/Dashboard/Index',[
                'laboratories' => $this->dropdown->laboratory_types(),
            ]);
        }
    }

    public function index(){
        if(\Auth::check()){
            return inertia('Modules/Laboratory/Dashboard/Index',[
                'laboratories' => $this->dropdown->laboratory_types(),
            ]);
        }else{
            return inertia('Auth/Login');
        }
    }



}
