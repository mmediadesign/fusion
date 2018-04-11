<?php
/**
* Page Controller: Entry point for generic pages
* @author Matt Anderson
* 
*/
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\PageTemplate;
use App\Page;

/**
* Page Controller: Class definition
* @author Matt Anderson
* 
*/
class PageController extends Controller
{
    private $_template;

    /**
    *
    * @author Matt Anderson
    * 
    */
    public function __construct(PageTemplate $_template) {
        $this->template = $_template;
    }

    /**
    * Enrty point for generic pages.
    * Added means of controlling template that is rendered using DB field 
    * in corresponding table.
    *
    * @author Matt Anderson
    * 
    */
    public function index(Request $request) {
        try {
            $strRequestPath = (string)$request->path();
            $objPageModel = Page::where('slug', (string)$strRequestPath)->first();
            if (!empty($objPageModel)) {
                $objPageTemplate = $this->template->get($objPageModel->template);               
                if ($objPageTemplate === false) {
                    throw new Exception('Page template not found');
                }
                return view()->file($objPageTemplate->path);
            }
        } catch(Exception $e) {
            return new Response(
                view('errors.404', 
                ['error' => $e->getMessage()]), 
                404
            );
        }
    }
}
