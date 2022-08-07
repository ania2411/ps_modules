<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class condesign_productsblocksajaxModuleFrontController extends ModuleFrontController
{

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();

    }

    public function initContent()
    {
        parent::initContent();
        $this->ajax = true;
    }
    public function displayAjax()
    {

        if(Tools::getIsset('action') && Tools::getValue('action') == 'list-categories') {
            $items = Category::getCategories($this->context->language->id);
            $result = [];
            foreach($items as $c) {
                $category = new Category($c['id_category'], $this->context->language->id);
                $id_parent = $category->id_parent;
                $category_to_write[] = $category->name;
                while($id_parent > 1) {
                    $category = new Category($id_parent, $this->context->language->id);
                    $id_parent = $category->id_parent;
                    $category_to_write[] = $category->name;
                }
                $result[] = ['id' => $category->id, 'text' => join(' / ', array_reverse($category_to_write))];
            }
            echo json_encode($result);
            die();
        }
    }

}
