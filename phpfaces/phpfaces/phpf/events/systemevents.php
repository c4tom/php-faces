<?php
class RequestEvent
{
  protected $listener =RequestListener;  
  public $method ; 
   public function RequestEvent()
    {
        return $this->method;
    }
    public function getMethod()
    {
        return $this->method;
    }
     public function getListener()
    {
        return $this->listener;
    }
}

class RequestEventFactory
{
public static function processEvent()
{
$isXmlHttpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest";
if ($isXmlHttpRequest)
return new AjaxEvent();
$request = $_SERVER["REQUEST_METHOD"];
return  objectFactory::getNewInstance(ucfirst(strtoupper($request))."Event");
}
}

class GetEvent extends RequestEvent
{
     public function GetEvent()
    {
     $this->method="requestGetAccept"; 
    }
}

class PostEvent extends RequestEvent
{
    public function PostEvent()
    {
     $this->method="requestPostAccept"; 
    }
  
}

class AjaxEvent extends RequestEvent
{
public function AjaxEvent()
{
$this->method="requestAjaxAccept";
}
}

class HeadEvent  extends RequestEvent
{}

class PutEvent extends RequestEvent
{}

class DeleteEvent extends RequestEvent
{}
?>
