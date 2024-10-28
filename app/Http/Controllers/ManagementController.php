<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Traits\HandlesTransaction;
use App\Services\Management\CampusClass;
use App\Services\Management\SchoolClass;
use App\Services\Management\CourseClass;
use App\Http\Requests\ManagementRequest;
use App\Services\Management\CertificationClass;

class ManagementController extends Controller
{
    use HandlesTransaction;

    public function __construct(SchoolClass $school, CourseClass $course, CertificationClass $certification,CampusClass $campus,DropdownClass $dropdown){
        $this->school = $school;
        $this->course = $course;
        $this->campus = $campus;
        $this->dropdown = $dropdown;
        $this->certification = $certification;
    }

    public function index(Request $request){
        switch($request->option){
            case 'courses':
                return $this->course->lists($request);
            break;
            case 'campuses':
                return $this->campus->lists($request);
            break;
            case 'schools':
                return $this->school->lists($request);
            break;
            default:
                return inertia('Modules/Management/Dashboard/Index'); 
        }   
    }

    public function store(ManagementRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'school':
                    return $this->school->save($request);
                break;
                case 'campus':
                    return $this->campus->save($request);
                break;
                case 'name':
                    return $this->campus->name($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'campus':
                    return $this->campus->update($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function show($code, Request $request){
        switch($code){
            case 'schools':
                return inertia('Modules/Management/Schools/Index',[
                    'dropdowns' => [
                        'classes' => $this->dropdown->classes(),
                    ]
                ]);
            break;
            case 'campuses':
                return inertia('Modules/Management/Campuses/Index',[
                    'dropdowns' => [
                        'gradings' => $this->dropdown->gradings(),
                        'terms' => $this->dropdown->terms(),
                        'regions' => $this->dropdown->regions(),
                        'agencies' => $this->dropdown->agencies()
                    ]
                ]);
            break;
            case 'school-view':
                return inertia('Modules/Management/Schools/View',[
                    'school' => $this->school->view($request),
                    'notes' => $this->school->notes(),
                    'dropdowns' => [
                        'gradings' => $this->dropdown->gradings(),
                        'terms' => $this->dropdown->terms(),
                        'regions' => $this->dropdown->regions(),
                        'agencies' => $this->dropdown->agencies()
                    ]
                ]);
            break;
            case 'school-create':
                return inertia('Modules/Management/Schools/Create',[
                    'dropdowns' => [
                        'classes' => $this->dropdown->classes(),
                        'gradings' => $this->dropdown->gradings(),
                        'terms' => $this->dropdown->terms(),
                        'regions' => $this->dropdown->regions(),
                        'agencies' => $this->dropdown->agencies()
                    ]
                ]);
            break;
            case 'courses':
                return inertia('Modules/Management/Courses/Index');
            break;
        }
    }
}
