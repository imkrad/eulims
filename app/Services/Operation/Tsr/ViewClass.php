<?php

namespace App\Services\Operation\Tsr;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Tsr;
use App\Models\TsrReport;
use App\Models\TsrAnalysis;
use App\Models\UserRole;
use App\Models\Configuration;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Http\Resources\Operation\TsrResource;
use App\Http\Resources\Operation\TsrViewResource;
use App\Http\Resources\Operation\AnalysisResource;

class ViewClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
        $data = UserRole::where('user_id',\Auth::user()->id)->pluck('laboratory_type');
        $filteredData = $data->filter(function ($value) {
            return !is_null($value);
        });
        $this->type = $filteredData->isNotEmpty() ? $filteredData : null;
    }

    public function counts($statuses){
        foreach($statuses as $status){
            $counts[] = Tsr::where('status_id',$status['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        $data = TsrResource::collection(
            Tsr::query()
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.wallet')
            ->with('customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,is_free,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer',function ($query) use ($keyword) {
                    $query->whereHas('customer_name',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
            })
            ->with(['samples' => function ($query){
                $query->select('id','tsr_id');
                $query->withCount([
                    'analyses as analyses_count',
                    'analyses as completed_analyses_count' => function ($query) {
                        $query->where('status_id', 12);
                    },
                    'analyses as ongoing_analyses_count' => function ($query) {
                        $query->where('status_id', 11);
                    }
                ]);
            }])
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($request->datetype && $request->date, function ($query) use ($request) {
                $query->whereDate($request->datetype, $request->date);
            })
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->when($request->laboratory , function ($query, $labtype ) {
                $query->where('laboratory_type',$labtype );
            }) 
            ->when($request->sort, function ($query, $sort) use ($request) {
                if($request->sortby == 'Code'){
                    $query->orderBy('code',$request->sort);
                }else if($request->sortby == 'Requested At'){
                    $query->orderBy('created_at',$request->sort);
                }else{
                    $query->orderBy('due_at',$request->sort);
                }
            })
            ->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Due Soon':
                        $query->whereBetween('due_at', [Carbon::now()->startOfDay(), Carbon::now()->addDays(5)->endOfDay()])->where('status_id','!=',4);
                    break;
                    case 'Overdue Request':
                        $query->where('status_id',3)->whereDate('due_at','<',now());
                    break;
                    case 'For Released':
                        $query->where('status_id',4)->where('due_at','>',now())->where('released_at',null)->where('laboratory_id',$this->laboratory) ->whereHas('samples', function ($query) {
                            $query->doesntHave('report');
                        }, '=', 0);
                    break;
                    case 'Unclaimed Reports':
                        $query->where('status_id',4)->where('due_at','<=', now()->subDays(30))->where('released_at',null)->where('laboratory_id',$this->laboratory)->whereHas('samples', function ($query) {
                            $query->doesntHave('report');
                        }, '=', 0);
                    break;
                }
            })
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);

        $data = new TsrViewResource(
            Tsr::query()
            ->with('samples.report','samples.analyses','samples.analyses.addfee.service','samples.analyses.testservice.testname','samples.analyses.testservice.method.method','samples.analyses.testservice.method.reference','samples.analyses.testservice.fees')
            ->with('service.service')
            ->with('children.child.status')
            ->with('groups.testservice:id,testname_id,method_id,laboratory_type','groups.testservice.testname:id,name','groups.testservice.type:id,name')
            ->with('received:id','received.profile:id,firstname,lastname,user_id')
            ->with('laboratory','laboratory_type:id,name','status:id,name,color,others')
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.wallet','customer.industry:id,name')
            ->with('customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name','customer.conformes')
            ->with('conforme:id,name,contact_no','customer.contact:id,email,contact_no,customer_id')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,is_free,has_deduction,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others','payment.collection:id,name','payment.type:id,name','payment.discounted:id,name,value','payment.deduction')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function analyses($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);

        $data = AnalysisResource::collection(
            TsrAnalysis::query()
            ->with('sample','status','analyst','addfee.service')
            ->with('testservice.testname','testservice.method.method','testservice.method.reference','testservice.fees')
            ->whereHas('sample',function ($query) use ($id){
                $query->whereHas('tsr',function ($query) use ($id){
                    $query->where('id',$id);
                });
            })
            ->get()
        );
        return $data;
    }

    public function print($request){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($request->id);

        $labcolor = Tsr::where('id',$id)->with('lab_type')->first();
        $tsr = TsrReport::where('tsr_id',$id)->value('information');
        $lab = json_decode($tsr);
    

        $head = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
       ->where('laboratory_type',$labcolor->lab_type->id)->whereHas('role',function ($query){
            $query->where('name','Technical Manager');
        })->first();

        $cashier = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->whereHas('role',function ($query){
            $query->where('name','Cashier');
        })->first();
        
        $url = $_SERVER['HTTP_HOST'].'/verification/'.$request->id;
        $qrCode = new QrCode($url);
        $qrCode->setSize(300);
        $pngWriter = new PngWriter();
        $qrCodeImageString = $pngWriter->write($qrCode)->getString();
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

        $array = [
            'qrCodeImage' => $base64Image,
            'configuration' => Configuration::first(),
            'tsr' => json_decode($tsr),
            'cashier' => $cashier->user->profile->firstname.' '.$cashier->user->profile->middlename[0].'. '.$cashier->user->profile->lastname,
            'manager' => $head->user->profile->firstname.' '.$head->user->profile->middlename[0].'. '.$head->user->profile->lastname,
            'user' => \Auth::user()->profile->firstname.' '.\Auth::user()->profile->middlename[0].'. '.\Auth::user()->profile->lastname,
            'color' => ($labcolor->lab_type) ? $labcolor->lab_type->color : 'black'
        ];

        $pdf = \PDF::loadView('reports.tsr',$array)->setPaper('a4', 'portrait');
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $copies = 3;
            $totalPagesPerCopy = $pageCount / $copies;
            $currentPageInCopy = ($pageNumber - 1) % $totalPagesPerCopy + 1;
            $text = "PAGE $currentPageInCopy OF $totalPagesPerCopy";
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 7;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $canvas->text(1 - $width, 1, $text, $font, $size);
        });
        return $pdf->stream($lab->code.'.pdf');
    }
}
