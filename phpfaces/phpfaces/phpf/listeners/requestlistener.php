<?php
interface RequestListener extends FacesListener
{
public function requestPostAccept();
public function requestAjaxAccept();
}
?>
