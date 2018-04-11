<?php

namespace App;

use Storage;

class PageTemplate
{
  public $templates;

  public function getAll()
  {
    $this->templates = [];
    foreach(Storage::disk('templates')->directories() as $dir){
        $objTemplate = $this->get($dir);
        if ($objTemplate !== false) {
            $this->templates[]=$objTemplate;
        }
    }
    return $this->templates;
  }

  public function get($strTemplateName = 'basic')
  {
    if (Storage::disk('templates')->exists("{$strTemplateName}/template.json") === false) {
        return false;
    }
    $strTemplateData = Storage::disk('templates')->get("{$strTemplateName}/template.json");
    if (empty($strTemplateData)) {
        return false;
    }
    $objData = json_decode($strTemplateData);
    $objData->id = $strTemplateName;
    $objData->path = Storage::disk('templates')->getDriver()->getAdapter()->getPathPrefix() . "{$strTemplateName}/{$objData->view}";
    return $objData;
  }
}
