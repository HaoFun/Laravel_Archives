@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $archive->title }}</div>
                    <div class="panel-body">
                        <div class="panel-body">
                            {!! $archive->body !!}
                        </div>
                        <form action="{{ route('archives.destroy',$archive->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger pull-right">刪除</button>
                        </form>
                        <a href="{{ route('archives.edit',$archive->id) }}" type="button" class="btn btn-primary pull-right">編輯</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection