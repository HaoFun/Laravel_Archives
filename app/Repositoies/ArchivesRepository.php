<?php
namespace App\Repositoies;

use App\Archives;
use App\Topics;

class ArchivesRepository
{
    public function ArchiveCreate(array $data)
    {
        return Archives::create($data);
    }

    public function ArchiveByID($id)
    {
        return Archives::findOrFail($id);
    }

    public function ArchiveByIDwithTopicsAndAnswers($id)
    {
        return Archives::where('id',$id)->with('topics','answers')->firstOrFail();
    }

    //取所有Archives，is_hidden不為true，依更新時間排序大到小
    public function ArchivesFeed()
    {
        return Archives::published()->latest('updated_at')->with('users')->get();
    }

    //判斷Topic 是否已存在，不存在就執行FindOrCreateTopic建立對應的Topic，
    //並執行對應的increment or decrement，返回array(TopicID)
    public function normalizeTopic(array $topics,$type = null)
    {
        return collect($topics)->map(function ($topic) use ($type) {
            //$topic 為數字
            if(is_numeric($topic))
            {
                $query = Topics::find($topic);
                return $this->findOrCreateTopic($query,$topic,$type);
            }
            //$topic 為字串
            $query = Topics::where('name',$topic)->first();
            return $this->findOrCreateTopic($query,$topic,$type);
        })->toArray();
    }

    //return Topic ID or Create Topic
    public function findOrCreateTopic($query,$topic,$type)
    {
        $do_type = ($query === null? 0:1) + ($type === null ? 0:10);
        switch ($do_type)
        {
            case 0:
                //不為update時，且有新的topic需創建
                $newTopic = Topics::create(['name' => $topic,'bio' => $topic,'archives_count' => 1]);
                return (int)$newTopic->id;
                break;
            case 1:
                //為update時，沒有新的topic需創建，針對使用舊有topic increment指定欄位增量
                $query->increment('archives_count');
                return (int)$query->id;
                break;
            case 10:
                //為update時，且有新的topic需創建
                $newTopic = Topics::create(['name' => $topic,'bio' => $topic,'archives_count' => 0]);
                return (int)$newTopic->id;
                break;
            default:
                return (int)$query->id;
                break;
        }
    }

    //map decrement Topic
    public function decrementTopic(array $topics)
    {
        collect($topics)->map(function ($topic){
            $this->TopicByID($topic['id'])->decrement('archives_count');
        });
    }

    //map increment Topic
    public function incrementTopic(array $topics)
    {
        collect($topics)->map(function ($topic){
            $this->TopicByID($topic)->increment('archives_count');
        });
    }

    public function TopicByID($id)
    {
        return Topics::findOrFail($id);
    }
}