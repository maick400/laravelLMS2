<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request};
use App\Models\{Category, Course, User, CourseUser,lesson_commentary};
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

use Jambasangsang\Flash\Facades\LaravelFlash;

class CourseController extends Controller
{

    public function index()
    {
        Gate::authorize('view_courses');
        abort_if(auth()->user()->hasRole('User'), 403);

        return view(
            'jambasangsang.backend.courses.index',
            [
                'courses' => auth()->user()->hasRole('Teacher') ? auth()->user()->userCourses()->with(['teacher:id,name', 'category:id,name'])->get() : Course::with(['teacher:id,name', 'category:id,name'])->get(),
                'categories' => Category::get(['id', 'name'])
            ]
        );
    }


    public function create(Request $request)
    {

        Gate::authorize('add_courses');
        $fields = ['name','description','summary','requirement','category_id','teacher_id','status','price','duration','started_at','finished_at','image'];
        $validator  = Validator ::make ($request->all(), $fields);

        if ($validator->fails()){
            return redirect()-> back() ->  withErrors($validator) -> withInput();
        }

        return view(
            'jambasangsang.backend.courses.create',
            [
                'teachers' => User::Teacher()->get(['id', 'name']),
                'categories' => Category::with('parents')->whereNull('parent_id')->where('status', 'enabled')->get(['id', 'name'])
            ]
        );
    }


    public function store(StoreCourseRequest $request)
    {
        Gate::authorize('add_courses');

        $course = Course::create($request->validated());
        $course->image  = uploadOrUpdateFile($request, $course->image, \constPath::CourseImage);
        $course->save();
        LaravelFlash::withSuccess('Course Created Successfully');
        return redirect()->route('courses.index');
    }



    public function suscribe(int $course)
    {
        CourseUser::create([
            'user_id' => auth()->user()->id,
            'course_id' => $course,
        ]);


        return redirect()->route('home');
    }

    public function show($slug)
    {
        Gate::authorize('view_courses');
        return view(
            'jambasangsang.backend.courses.show',

            ['course' =>  Course::with('teacher:id,name,email', 'category:id,name', 'lessons')->whereSlug($slug)->first(),
            'lesson_commentaries' =>lesson_commentary ::all(),



            ]
        );
    }

    public function enroll(int $course)
    {
        CourseUser::create([
            'user_id' => auth()->user()->id,
            'course_id' => $course,
        ]);


        return view('jambasangsang.frontend.courses.single', [
            'related_courses' => Course::FrontEndCourse()->with('teacher:id,name,image,slug')->where('category_id', $course->category_id)->where('id', '!=', $course->id)->inRandomOrder()->take(2)->get(),
            'course' => $course->load('teacher:id,name,image,slug', 'students:id', 'category:id,name,slug', 'lessons', 'reviews'),
            'courses_you_may_like' => Course::FrontEndCourse()->with('teacher:id,name,image,slug')->where('id', '!=', $course->id)->inRandomOrder()->take(3)->get(),
        ]);
    }


    public function edit(Course $course)
    {
        Gate::authorize('edit_courses');
        return view('jambasangsang.backend.courses.edit', [

            'course' => $course, 'teachers' => User::Teacher()->get(['id', 'name']),
            'categories' => Category::with('parents')->whereNull('parent_id')->where('status', 'enabled')->get(['id', 'name'])
        ]);
    }


    public function update(UpdateCourseRequest $request, Course $course)
    {
        Gate::authorize('edit_courses');

        $course->update($request->validated());
        $course->image  = uploadOrUpdateFile($request, $course->image, \constPath::CourseImage);
        $course->save();
        LaravelFlash::withSuccess('Course Updated Successfully');
        return redirect()->route('courses.index');
    }


    public function destroy($id)
    {
        Gate::authorize('delete_courses');
    }
}
