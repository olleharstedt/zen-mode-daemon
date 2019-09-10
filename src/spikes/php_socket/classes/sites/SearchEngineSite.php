<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;

class SearchEngineSite extends SiteBase
{
    /**
     * @return string
     */
    public function getContent(): string
    {
        if (isset($this->config->form_name)) {
            $formName = $this->config->form_name;
            $dom = new Dom();
            $dom->loadFromUrl($this->url);
            $form = $dom->find("form[name=$formName]");
        } elseif (isset($this->config->form_id)) {
            $formId = $this->config->form_id;
            $dom = new Dom();
            $dom->loadFromUrl($this->url);
            $form = $dom->find("#$formId");
        }
        $content = "<!DOCTYPE html><html><head></head><body>";
        $content .= "<h1>{$this->config->name}</h1>";
        $form = $this->replaceFormAction($form);
        $content .= (string) $form;
        $content .= "</body></html>";
        return $content;
    }

    /**
     * @param string $form Form HTML
     * @return string
     */
    protected function replaceFormAction(string $form): string
    {
        
    }
}
