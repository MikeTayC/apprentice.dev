<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:31 AM
 */
class Incubate_Model_TagMap extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'lesson_tag_map';
        parent::__construct();
    }

    public function createTagMap($lesson_id, $tag_id)
    {
        $this->_db->insert($this->_table, array(
            'lesson_id' => $lesson_id,
            'tag_id' => $tag_id
        ));
    }
}