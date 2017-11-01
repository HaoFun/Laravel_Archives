@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $archive->title }}
                        @foreach($archive->topics as $topic)
                            {{--print topic--}}
                            <a class="topic pull-right" href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
                        @endforeach
                    </div>
                    <div class="panel-body">
                        {!! $archive->body !!}
                        <div class="actions pull-right">
                            @if(Auth::check() && Auth::user()->owns($archive))
                                <a style="margin-right: 5px" href="{{ route('archives.edit',$archive->id) }}" type="button" class="btn btn-primary pull-right">編輯</a>
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
            <div class="col-md-8 col-md-offset-2">
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
                                <textarea id="container" name="body" style="height: 120px">{!! old('body') !!}</textarea>
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                    <strong>{!! $errors->first('body') !!}</strong>
                                </span>
                                @endif
                            </div>
                            <button class="btn btn-primary pull-right" type="submit">確　認</button>
                        </form>
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
    </script>
@endsection
