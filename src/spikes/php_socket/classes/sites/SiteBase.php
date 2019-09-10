<?php

namespace zenmodedaemon\classes\sites;

class SiteBase
{
    /**
     * @var object
     */
    protected $config;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param object $config
     */
    public function __construct(object $config)
    {
        $this->config = $config;
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
     * @param object $config
     * @return SiteBase
     * @throws Exception
     */
    public static function resolveSiteType(object $config): SiteBase
    {
        switch ($config->type) {
            case 'search_engine':
                return new SearchEngineSite($config);
                break;
            default:
                throw new \Exception('Found no site class with type ' . $type);
                break;
        }
    }
}
