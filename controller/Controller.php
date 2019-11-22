<?php
abstract class Controllers{
    
    private $isPublic = false;
    
    public function index($params) {
        echo TwigUtility::getInstance()->render(strtolower(get_called_class()) . ".html.twig", $params);
    }


}