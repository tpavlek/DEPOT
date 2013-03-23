<?php

class Replay extends Page {

    private $rid;
    private $path;

    public function __construct($rid) {
        parent::__construct();
        $data = $this->db->getReplay($rid);
        $this->rid = $rid;
        $this->path = $data['path'];
    }

    function getRID() {
        return $this->rid;
    }

    function getPath() {
        return $this->path;
    }

}

?>