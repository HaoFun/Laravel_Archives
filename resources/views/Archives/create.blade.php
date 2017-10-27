@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">發布文章</div>
                    <div class="panel-body">
                        <form action="{{ route('archives.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('title')?'has-error':''  }}">
                                <label for="title">文章標題</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" placeholder="請填寫標題">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('title') !!}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('body')?'has-error':''  }}">
                                <label for="body">文章內容</label>
                                <textarea id="container" name="body" style="height: 200px">{!! old('body') !!}</textarea>
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                    <strong>{!! $errors->first('body') !!}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary form-control">確認</button>
                            </div>

                        </form>
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
