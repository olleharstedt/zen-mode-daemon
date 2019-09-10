<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Tag;
use PHPHtmlParser\Dom\HtmlNode;

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
        $form = $this->replaceFormAction($dom);
        $content .= (string) $form;
        $content .= "</body></html>";
        return $content;
    }

    /**
     * @param string $form Form HTML
     * @return string
     * @throws Exception
     */
    protected function replaceFormAction(Dom $dom): string
    {
        $form = $dom->find('form');
        $form->setAttribute('action', '/');
        $form->setAttribute('method', 'GET');
        $input = new HtmlNode('input');
        $input->tag->selfClosing();
        $input->setAttribute('type', 'hidden');
        $input->setAttribute('name', '__site');
        $urlMinusHttps = substr($this->url, 8);
        $input->setAttribute('value', $urlMinusHttps);
        $firstInput = $form->find('input')[0];
        if (!$form->addChild($input)) {
            throw new \Exception('Could not add input to form');
        }
        return (string) $form;
    }
}
