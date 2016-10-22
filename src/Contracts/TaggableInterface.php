<?php namespace Lecturize\Tags\Contracts;

interface TaggableInterface
{
    /**
     * @return mixed
     */
    public function tags();

    /**
     * @return mixed
     */
    public function tagged();

    /**
     * @param $tags
     * @return mixed
     */
    public function tag($tags);

    /**
     * @param $tags
     * @return mixed
     */
    public function untag($tags);

    /**
     * @param $tags
     * @return mixed
     */
    public function retag($tags);

    /**
     * @return mixed
     */
    public function detag();

    /**
     * @param $tag
     * @return mixed
     */
    public function hasTag($tag);

    /**
     * @return mixed
     */
    public function listTags();
}