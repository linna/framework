<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Mvc;

/**
 * View
 * - This class manage rendering of html page
 * Css, javascript and data.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class View
{
    /**
     * @var array Contain path to css file
     */
    protected $css = array();

    /**
     * @var array Contain path to js file
     */
    protected $js = array();

    /**
     * @var string Page title
     *
     * @todo Implements the property in header template
     */
    protected $title = null;

    /**
     * @var objec Data for the dynamic view
     */
    public $data = null;

    /**
     * View constructor.
     * 
     * @since 0.1.0
     */
    public function __construct()
    {
        //set $data as object
        $this->data = (object) null;

        //standard js file
        //$this->js[] = URL . 'js/jquery-2.1.4.min.js';
        //$this->js[] = URL . 'js/main.js';
        //$this->js[] = URL . 'js/ajax.js';
        //$this->js[] = URL . 'js/application.js';

        //standard css file
        //$this->css[] = URL . 'css/style.css';

        $this->title = 'App';
    }

    /**
     * addCss.
     * 
     * Called from controller
     * Utilize this for add a file css to view from crontroller
     * 
     * @param string $file Path for css file
     *
     * @since 0.1.0
     */
    public function addCss($file)
    {
        $this->css[] = URL.$file;
    }

    /**
     * addJs.
     * 
     * Called from controller
     * Utilize this for add a file js to view from crontroller
     * 
     * @param string $file Path for js file
     *
     * @since 0.1.0
     */
    public function addJs($file)
    {
        $this->js[] = URL.$file;
    }

    /**
     * addJs.
     * 
     * Called from controller
     * Utilize this for bring title to html page generate from View
     * 
     * @param string $title Path for css file
     *
     * @since 0.1.0 
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * render.
     * 
     * Called from controller
     * Send view to browser
     * 
     * @param string $view The view called from controller
     *
     * @throws \Exception if view path is incorrect
     *
     * @since 0.1.0
     */
    public function render($view)
    {
        $data = $this->data;
        $css = $this->css;
        $js = $this->js;

        $title = $this->title;

        ob_start();

        try {
            if (!file_exists(APP."Views/{$view}.html")) {
                throw new \Exception("The required View ({$view}) not exist.");
            }

            require APP.'Views/_templates/header.html';
            require APP."Views/{$view}.html";
            require APP.'Views/_templates/footer.html';
        } catch (\Exception $e) {
            echo 'View exception: ', $e->getMessage(), "\n";
        }

        ob_end_flush();
    }
}
