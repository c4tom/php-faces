<?php
interface ActionListener extends FacesListener
{
/**
 * @param ActionEvent $event
 */
public function actionPerformed(ActionEvent $event);
}
?>
