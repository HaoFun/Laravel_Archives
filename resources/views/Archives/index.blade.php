@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <button id="button_notifi">測試</button>
            <div class="panel panel-default">
                <div class="panel-body">
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
                                </div>
                            </div>
                        @endforeach
                        @auth
                        <a style="margin-top: 30px" class="btn btn-primary form-control" href="{{ route('archives.create') }}">新增文章</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        if (window.Notification)
        {
            var button = document.getElementById('button_notifi'), text = document.getElementById('text');
            var popNotice = function() {
                if (Notification.permission == "granted") {
                    var notification = new Notification("試試：", {
                        body: 'Test123',
                        icon: 'images/avatar/default.jpg'
                    });
                }
                notification.onclick = function(event) {
                    event.preventDefault();
                    window.open('http://haofun.app/', '_blank');
                };
            };

            button.onclick = function() {
                if (Notification.permission == "granted") {
                    popNotice();
                } else if (Notification.permission != "denied") {
                    Notification.requestPermission(function (permission) {
                        popNotice();
                    });
                }
            };
        }
    </script>
@endsection