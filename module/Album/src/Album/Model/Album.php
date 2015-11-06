<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 04.11.2015
 * Time: 13:24
 */

namespace Album\Model;

class Album {
    public $id;
    public $artist;
    public $title;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
    }

}