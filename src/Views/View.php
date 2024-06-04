<?php 
namespace Src\Views;

class View {
    private $layout;
    private $admin_layout;

    private $extraVars = [];

    public function __construct(string $layout) {
        $this->layout = $layout;
    }

     public function renderAdmin(string $viewName, array $vars = [], int $code = 200) {
        http_response_code($code);
        $layoutFile = "Admin/Main/{$this->layout}.php";
        $content = $this->renderFile($viewName, $vars); 
        echo $this->renderFile($layoutFile, ['content' => $content]);
    } 

    public function setVar(string $name, $value): void{
        $this->extraVars[$name] = $value;
    }
    public function renderHtml(string $viewName, array $vars = [], int $code = 200) {
        http_response_code($code);
        $layoutFile = "Layouts/{$this->layout}.php";
        $content = $this->renderFile($viewName, $vars); 
        echo $this->renderFile($layoutFile, ['content' => $content]);
    } 

    private function renderFile(string $fileName, array $vars) {
        extract($this->extraVars);
        extract($vars); 
        $fileName = __DIR__ . "/" . $fileName;
        if (file_exists($fileName)) {
            ob_start();
            include $fileName;
            $buffer = ob_get_contents();
            ob_get_clean();
            return $buffer;
        } else {
            echo "Не найден файл по пути $fileName"; die();
        }
    }
}