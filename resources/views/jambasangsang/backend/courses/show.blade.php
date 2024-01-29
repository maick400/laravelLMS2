@extends('layouts.backend.master')
@section('css')
    <style>
        .title {
            background-color: rgba(7, 41, 77, 0.8) !important;
            padding: 10px;
            cursor: pointer;
        }

        .title .btn-link {
            text-decoration: none;
            color: white;
        }

        .title strong {
            text-decoration: none;
            color: white;
        }

        .title .btn-link:hover {
            text-decoration: none;
            color: white;
        }

        .card-title {
            text-transform: uppercase;
            font-family: 'poppin';
        }

        body {
            font-family: 'poppin'
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <section class="card">
                <div class="twt-feed blue-bg">
                    <div class="corner-ribon black-ribon">
                        <i class="fa fa-twitter"></i>
                    </div>
                    <div class="fa fa-twitter wtt-mark"></div>

                    <div class="media">
                        <a href="#">
                            <img class="align-self-center rounded-circle mr-3" style="width:85px; height:85px;" alt=""
                                src="{{ $course->teacher->avatar }}">
                        </a>
                        <div class="media-body">
                            <h3 class="text-white display-6">{{ $course->teacher->name }}</h3>
                            <p class="text-light">{{ $course->teacher->assignRoles() }}</p>
                        </div>
                    </div>

                </div>
                <div class="weather-category twt-category">
                    <ul>
                        <li class="active">
                            <h5>{{ count($course->students) }}</h5>
                            @lang('Students')
                        </li>
                        <li>
                            <h5>865</h5>
                            Following
                        </li>
                        <li>
                            <h5>{{ count($course->lessons) }}</h5>
                            @lang('Lessons')
                        </li>
                    </ul>
                </div>
                <div class="twt-write col-sm-12">
                    <textarea placeholder="Write your Tweet and Enter" rows="1" class="form-control t-text-area"></textarea>
                </div>
                <footer class="twt-footer">
                    <a href="#"><i class="fa fa-camera"></i></a>
                    <a href="#"><i class="fa fa-map-marker"></i></a>
                    New Castle, UK
                    <span class="pull-right">
                        32
                    </span>
                </footer>
            </section>
            @foreach ($course->students as $student)
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><img src="{{ $student->image() }}" alt="" height="60">
                            </div>
                            <div class="stat-content dib">
                                <div class="stat-text text-lg"><a
                                        @can('profile_students') href="{{ route('students.show', $student->slug) }}" @endcan>{{ $student->name }}</a>
                                </div>
                                <div class="stat-digit"> <i class="ti-user text-success border-success"></i>
                                    {{ $student->students_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-8">
            <div class="card">
                <img class="card-img-top" src="{{ $course->image() }}" alt="{{ $course->name }}" height="300">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $course->name }}
                        @can('add_lessons')
                            <a class="pull-right" href="{{ route('lessons.create', [$course->slug]) }}"><i
                                    class="fa fa-address-card" aria-hidden="true"></i> Add Lesson</a>
                        @endcan

                    </h4>

                    <div class="accordion" id="accordionExample">
                        @forelse ($course->lessons as $key => $lesson)
                            <div class="card">
                                <div
                                    class="card-header" id="headingOne{{ $lesson->id }}" data-toggle="collapse"
                                    data-target="#collapseOne{{ $lesson->id }}">
                                    <div class="row">

                                        <div class="col-2">
                                            <img class="w-100"  src="{{ $lesson->image() }}" alt="">
                                        </div>
                                        <div class="col-10">

                                            <h2 class="mb-0 title">
                                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne{{ $lesson->id }}" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    @lang('Lesson ' . $key + 1)
                                                </button>
                                                <strong style="font-size: 18px !important"
                                                    class="pull-right">{{ $lesson->title }} <br><span
                                                        class="pull-right"><i class="fa fa-clock-o"
                                                            aria-hidden="true"></i>{{ $lesson->duration }}</span></strong>
                                            </h2>
                                        </div>
                                    </div>
                                </div>


                                <div id="collapseOne{{ $lesson->id }}" class="collapse"
                                    aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        {!! $lesson->content !!}
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$lesson -> video}}?si=b6dYmAJTJonp8lbY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                    </div>

                                    <div class="commentaries">
                                        Comentarios

                                            <div class="row d-flex justify-content-start">

                                                <div class="col-md-12 col-lg-12">
                                                  <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                                                    <div class="card-body p-4">
                                                      <div class="form-outline mb-4">
                                                        <input type="text" id="addANote" class="form-control" placeholder="Type comment..." />
                                                        <label class="form-label" for="addANote">+ Add a note</label>
                                                      </div>
                                                      @foreach ($lesson_commentaries as $comm)

                                                        @if ($lesson->id == $comm->lesson_id)
                                                      <div class="card mb-4">
                                                        <div class="card-body">
                                                          <p>{{$comm->user_id}}</p>

                                                          <div class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row align-items-center">
                                                              <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(4).webp" alt="avatar" width="25"
                                                                height="25" />
                                                              <p class="small mb-0 ms-2">{{$comm->commentario}}</p>
                                                            </div>
                                                            <div class="d-flex flex-row align-items-center">
                                                              <p class="small text-muted mb-0">Upvote?</p>
                                                              <i class="far fa-thumbs-up mx-2 fa-xs text-black" style="margin-top: -0.16rem;"></i>
                                                              <p class="small text-muted mb-0">3</p>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      @endif

                                        @endforeach





                                                    </div>
                                                  </div>
                                                </div>
                                              </div>





                                    </div>

                                </div>


                            </div>
                        @empty
                            <div class="flex justify-between">
                                <h2 class="text-center">No Lesson</h2>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection