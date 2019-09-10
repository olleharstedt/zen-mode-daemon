<?php

namespace zenmodedaemon\classes\sites;

class SiteBase
{
    /**
     * @var object
     */
    protected $config = null;

    /**
     * @var array
     */
    protected $get = [];

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @param object $config
     */
    public function __construct(object $config, array $get)
    {
        $this->config = $config;
        $this->get = $get;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getHtml(string $content): string
    {
        $html = "<!DOCTYPE html><html><head>"
            . "<style type='text/css'>body{margin:40px
            auto;max-width:650px;line-height:1.6;font-size:18px;color:#333;padding:0 10px}h1,h2,h3{line-height:1.2}</style>"
            . "<meta charset='UTF-8'>"
            ."</head><body>";
        $html .= "<h1>{$this->config->name}</h1>";
        $html .= $content;
        $html .= "</body></html>";
        return $html;
    }

    /**
     * @param object $config
     * @param array $get
     * @return SiteBase
     * @throws Exception
     */
    public static function resolveSiteType(object $config, array $get): SiteBase
    {
        switch ($config->type) {
            case 'search_engine':
                if (count($get) === 1
                    && isset($get['__site'])) {
                    return new SearchEngineSite($config, $get);
                } else {
                    return new SearchEngineResultSite($config, $get);
                }
                break;
            default:
                throw new \Exception('Found no site class with type ' . $type);
                break;
        }
    }
}
