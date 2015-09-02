<?php namespace vendocrat\Tags\Contracts;

interface TaggableInterface
{
	public function tags();
	public function tag($tags);
	public function untag($tags);
	public function retag($tags);
	public function detag();
	public function hasTag($tag);
	public function getTags();
}