<?php
$form = (AmcWm::app()->frontend['bootstrap']['use']) ? '_registerBootstrapForm' : '_registerForm';
$pageContent = $this->renderPartial($form, array('enableSubscribe' => $enableSubscribe, 'contentModel' => $contentModel), true);
$breadcrumbs[] = AmcWm::t("msgsbase.core", "User Registration");
$this->widget('PageContentWidget', array(
    'id' => 'register-user',
    'contentData' => $pageContent,
    'title' =>  AmcWm::t("msgsbase.core", 'Join us') ,
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));