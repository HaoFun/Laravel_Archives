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
        return 'index';
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
        $archive = $this->archivesRepository->create($data);
        //topics 大於0 才進行 attach 多對多附加
        if(count($request->get('topics')) > 0)
        {
            $topics = $this->archivesRepository->normalizeTopic($request->get('topics'));
            $archive->topics()->attach($topics);
        }
        return redirect()->route('archives.show',['id' => $archive->id]);
    }

    public function show($id)
    {
        $archive = $this->archivesRepository->ArchiveByIDwithTopics($id);
        return view('archives.show',compact('archive'));
    }

    public function edit($id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        return view('archives.edit',compact('archive'));
    }

    public function update(ArchivesRequest $request, $id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        $before_topics = $archive->topics->pluck('id')->toArray();
        //更新topics大於0
        if(count($request->get('topics')) > 0)
        {
            $after_topics = $this->archivesRepository->normalizeTopic($request->get('topics'),'update');
            //因更新normalizeTopic 不會對原有的topics以及原本topics table 有的topic 進行increment，出來的topics
            //，只會increment topics table 中不存在的紀錄，創建該topic並archives_count設為1

            //第一種情況為原有topics有移除的動作，這邊先decrement 這些 舊有topics
            if($diff_topics = array_diff($before_topics,$after_topics))
            {
                $this->archivesRepository->decrementTopic($diff_topics);
            }
            //第二種情況為除了原有的topics 還有新增的topics(這邊指的 新增topics是已經存在於topics table紀錄中，不是create出來的)
            //，這邊針對新增的topics進行increment
            if($diff_topics = array_diff($after_topics,$before_topics))
            {
                $this->archivesRepository->incrementTopic($diff_topics);
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
            $this->archivesRepository->decrementTopic($before_topics);
            $archive->topics()->sync(array());
        }
        return view('archives.show',compact('archive'));
    }

    public function destroy($id)
    {
        $archive = $this->archivesRepository->ArchiveByID($id);
        if($archive !== null)
        {
            //對Archive中有使用的topic--decrment Topic
            $this->archivesRepository->decrementTopic($archive->topics);
            //detach() 解除多對多關係
            $archive->topics()->detach();
            $archive->delete();
            return action('ArchivesController@index');
        }
        flash('刪除失敗，請重試')->error();
        return back();
    }
}
