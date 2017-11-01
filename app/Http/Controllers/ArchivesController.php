<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArchivesRequest;
use App\Repositoies\ArchivesRepository;
use Auth;

class ArchivesController extends Controller
{
    protected $archivesRepository;

    public function __construct(ArchivesRepository $archivesRepository)
    {
        $this->middleware(['auth'])->except('index','show');
        $this->archivesRepository = $archivesRepository;
    }

    public function index()
    {
        $archives = $this->archivesRepository->ArchivesFeed();
        return view('Archives.index',compact('archives'));
    }

    public function create()
    {
        return view('Archives.create');
    }

    public function store(ArchivesRequest $request)
    {
        $data = [
            'title'   => $request->get('title'),
            'body'    => $request->get('body'),
            'user_id' => Auth::user()->id
        ];
        $archive = $this->archivesRepository->ArchiveCreate($data);
        //topics 大於0 才進行 attach 多對多附加
        if(count($request->get('topics')) > 0)
        {
            $topics = $this->archivesRepository->normalizeTopic($request->get('topics'));
            $archive->topics()->attach($topics);
        }
        flash()->success("新增文章---{$archive->title}成功");
        return redirect()->route('archives.show',['id' => $archive->id]);
    }

    public function show($id)
    {
        $archive = $this->archivesRepository->ArchiveByIDwithTopicsAndAnswers($id);
        return view('archives.show',compact('archive'));
    }

    public function edit($id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        //判斷編輯者是否為文章發表人，具有編輯權限
        if(Auth::user()->owns($archive))
        {
            return view('archives.edit',compact('archive'));
        }
        alert()->error('您沒有編輯權限!')->autoclose(1000);
        return back();
    }

    public function update(ArchivesRequest $request, $id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        $before_topics = $archive->topics->pluck('id')->toArray();
        //topics大於0，動作
        if(count($request->get('topics')) > 0)
        {
            $after_topics = $this->archivesRepository->normalizeTopic($request->get('topics'),'update');
            //因更新normalizeTopic 不會對原有的topics以及原本topics table 有的topic 進行increment，出來的topics
            //，只會increment topics table 中不存在的紀錄，創建該topic並archives_count設為1

            //第一種情況為原有topics有移除的動作，這邊先decrement 這些 舊有topics
            if($before_diff_topics = array_diff($before_topics,$after_topics))
            {
                $this->archivesRepository->decrementTopic($before_diff_topics);
            }
            //第二種情況為除了原有的topics 還有新增的topics(這邊指的 新增topics是已經存在於topics table紀錄中，不是create出來的)
            //，這邊針對新增的topics進行increment
            if($after_diff_topics = array_diff($after_topics,$before_topics))
            {
                $this->archivesRepository->incrementTopic($after_diff_topics);
            }
            $archive->update([
                'title' => $request->get('title'),
                'body'  => $request->get('body')
            ]);
            //sync 同步多對多關聯
            $archive->topics()->sync($after_topics);
        }
        else
        {
            $archive->update([
                'title' => $request->get('title'),
                'body'  => $request->get('body')
            ]);
            //如更新內容中 沒有包含任何topic，將原有的topics decrement
            $this->archivesRepository->decrementTopic($before_topics);
            $archive->topics()->sync(array());
        }
        flash()->success("編輯{$archive->title}成功");
        return redirect()->route('archives.show',['id' => $archive->id]);
    }

    public function destroy($id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        //判斷編輯者是否為文章發表人，具有刪除權限
        if(Auth::user()->owns($archive))
        {
            //對Archive中有使用的topic--decrment Topic
            $this->archivesRepository->decrementTopic($archive->topics);
            //detach() 解除多對多關係
            $archive->topics()->detach();
            $archive->delete();
            return action('ArchivesController@index');
        }
        alert()->error('刪除失敗，請重試')->autoclose(1000);
        return back();
    }
}
