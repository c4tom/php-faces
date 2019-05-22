<?php
class EventValidation
{
    public $id;
    public $key;
    public $time;
    public function EventValidation()
    {
        $this->time = time();
        $this->key =md5(uniqid(rand(), true));
        return $this;
    }
    public function toSerialize()
    {
        return base64_encode(serialize($this->key));
    }
}
?>