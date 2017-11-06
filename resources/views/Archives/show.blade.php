@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $archive->title }}
                        @foreach($archive->topics as $topic)
                            {{--print topic--}}
                            <a class="topic pull-right" href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
                        @endforeach
                    </div>
                    <div class="panel-body" style="min-height: 170px">
                        {!! $archive->body !!}
                        <div class="actions pull-right form-inline">
                            @if(Auth::check() && Auth::user()->owns($archive))
                                <a style="margin-right: 5px;" href="{{ route('archives.edit',$archive->id) }}" type="button" class="btn btn-primary pull-right">編輯</a>
                                <form action="{{ route('archives.destroy',$archive->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger">刪除</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-3">
                <archive-follow :check="{{ Auth::check() ? '1':'0' }}" :archive="{{ $archive->id }}"></archive-follow>
            </div>

            <div class="col-md-9 col-sm-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $archive->answers_count }}個回覆
                    </div>
                    <div class="panel-body content">
                        @foreach($archive->answers as $answer)
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img width="48" src="{{ $answer->users->avatar }}" alt="{{ $answer->users->name }}">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <p>{!! $answer->body !!}</p><span class="pull-right">{{ $answer->users->name }} 發表於 {{ $answer->created_at->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        @endforeach
                        @auth
                        <form action="{{ route('answer.store',$archive->id) }}" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group {{ $errors->has('body')?'has-error':'' }}">
                                <label for="body">回覆內容</label>
                                <textarea id="container" name="body" class="body" style="height: 120px">{!! old('body') !!}</textarea>
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                    <strong>{!! $errors->first('body') !!}</strong>
                                </span>
                                @endif
                            </div>
                            <button class="btn btn-primary pull-right" type="submit">確　認</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary form-control">請先登入再進行回覆</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('vendor.ueditor.assets')
    <script type="text/javascript">
        $(function () {
            //        實例化編輯器
            var ue = UE.getEditor('container', {
                toolbars: [
                    ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
                ],
                elementPathEnabled: false,
                enableContextMenu: false,
                autoClearEmptyNode:true,
                wordCount:false,
                imagePopup:false,
                autotypeset:{ indent: true,imageBlockLine: 'center' }
            });

            $('#write').click(function () {
                var element = document.getElementById("container");
                ue.focus(true);
                element.scrollIntoView(true);
            });
        });
    </script>
@endsection
