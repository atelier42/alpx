<?php

class FrontController extends FrontControllerCore
{
    public function setMedia()
    {
        parent::setMedia();
        $pageCategory = array('category','best-sales');
        $pageProduct = array('product');

        $page = $this->context->controller->php_self;

        $this->registerStylesheet('theme-responsive', '/assets/css/responsive.css', ['media' => 'all', 'priority' => 1007]);
        $this->registerStylesheet('theme-slick', '/assets/css/slick.css', ['media' => 'all', 'priority' => 1001]);
        if (in_array($page ,$pageCategory)) {
            $this->registerStylesheet('theme-category', '/assets/css/category.css', ['media' => 'all', 'priority' => 1008]);
        }
        if (in_array($page ,$pageProduct)) {
            $this->registerStylesheet('theme-product', '/assets/css/product.css', ['media' => 'all', 'priority' => 1005]);
        }
        $this->registerStylesheet('theme-compte', '/assets/css/compte.css', ['media' => 'all', 'priority' => 1009]);

        $this->registerJavascript('slick', '/assets/js/slick.min.js', ['media' => 'all', 'priority' => 800]);

    }
}