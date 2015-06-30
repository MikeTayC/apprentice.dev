<?php

class Blog_Model_Post
{
    public $author;
    public $body;
    public $entry;

    public function __construct()
    {
        $this->entry = "{$this->author} posted: {$this->body}";
    }
}