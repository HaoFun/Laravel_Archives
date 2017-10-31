@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">修改文章</div>
                    <div class="panel-body">
                        <form action="{{ route('archives.update',$archive->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group {{ $errors->has('title')?'has-error':''  }}">
                                <label for="title">文章標題</label>
                                <input type="text" id="title" name="title" value="{{ $archive->title }}" class="form-control" placeholder="請填寫標題">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('title') !!}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <select name="topics[]" class="select2-multiple-topics form-control" style="height: 50px" multiple="multiple">
                                    @foreach($archive->topics as $topic)
                                        <option value="{{ $topic->id }}" selected="selected">{{ $topic->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group {{ $errors->has('body')?'has-error':''  }}">
                                <label for="body">文章內容</label>
                                <textarea id="container" name="body" style="height: 200px">{!! $archive->body !!}</textarea>
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
        $(function () {
            function formatTopic (topic)
            {
                if(topic.name)  //排除undefined
                {
                    return "<option value='" + topic.name + "'>" +
                        topic.name  + "</option>";
                }
            }
            function formatTopicSelection (topic)
            {
                return topic.name || topic.text;      //name 後端返回的，如後端沒有查詢到，則使用用戶輸入的text
            }
            //引入select2
            $('.select2-multiple-topics').select2({
                tags: true,
                placeholder: '請選擇相關話題',
                minimumInputLength: 1,
                ajax: {
                    url: '/api/topics',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {          //取得輸入的字元  JSON格式{term:"PHP",_type:"query"}
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function (data) {   //返回搜尋結果
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: formatTopic,               //返回搜尋下拉的樣式
                templateSelection: formatTopicSelection,   //返回搜尋的內容
                escapeMarkup: function (markup) { return markup; }
            });
        })
    </script>
@endsection
