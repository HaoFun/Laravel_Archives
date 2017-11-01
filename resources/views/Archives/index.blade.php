@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($archives as $archive)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img width="48" src="{{ $archive->users->avatar }}" alt="{{ $archive->users->name }}">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="{{ route('archives.show',$archive->id) }}">{{ $archive->title }} <span class="pull-right">{{ $archive->users->name }} 發表於 {{ $archive->created_at->format('Y-m-d') }}</span></a>
                            </h4>
                            <p>{!! str_limit($archive->body,100) !!}</p>
                        </div>
                    </div>
                @endforeach
                @auth
                    <a class="btn btn-success form-control" href="{{ route('archives.create') }}">新增文章</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
