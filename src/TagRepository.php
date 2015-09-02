<?php namespace vendocrat\Tags;

use App\vendocrat\Core\Repository;
use Illuminate\Support\Collection;

class TagRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'vendocrat\Tags\Models\Tag';
    }

    public function getAllTagsBySlug($slugString)
    {
        if (is_null($slugString)) {
            return new Collection;
        }

        if (stristr($slugString, ',')) {
            $slugSlugs = explode(',', $slugString);
        } else {
            $slugSlugs = (array) $slugString;
        }

        return $this->model->whereIn('slug', (array) $slugSlugs)->get();
    }

    public function getTagIdList()
    {
        return $this->model->lists('id');
    }

    public function getTagsByIds($ids)
    {
        if ( ! $ids) return null;
        return $this->model->whereIn('id', $ids)->get();
    }

    public function getAllForForum()
    {
        return $this->model->where('forum', '=', 1)->get();
    }

    public function getAllForArticles()
    {
        return $this->model->where('articles', '=', 1)->get();
    }
}
