<?php

namespace JDare\Acetone;

use Config;
use App;
use Guzzle\Http\Client, Guzzle\Http\Exception\ClientErrorResponseException;
use JDare\Acetone\Exceptions\AcetoneException;

class Acetone
{

    private $server, $forceException;

    public function __construct()
    {
        $this->server = trim(Config::get("acetone::server.address"), "/");
        if (!$this->server)
            throw new AcetoneException("Varnish server address configuration must be specified");
        if (strpos($this->server, "http://") === false)
        {
            $this->server = "http://" . $this->server;
        }

        $this->forceException = Config::get("acetone::force_exceptions", 'auto');
    }

    /**
     * Will purge a single URL from the Varnish Cache. Accepts arrays of multiple URLs.
     * Warning: if purging many URLs, use BAN instead, it has significant performance benefits.
     *
     *
     * @param $url
     * @return bool True on success, False on failure
     * @throws Exceptions\AcetoneException
     */
    public function purge($url)
    {
        if (is_array($url))
            array_map(array($this, "purge"), $url);

        $url = parse_url($url);
        $path = null;
        if (isset($url['path']))
            $path = $url['path'];
        else
            throw new AcetoneException("URL to Purge could not be parsed");

        $client = new Client($this->server);
        $request = $client->createRequest("PURGE", $path);
        try {
            $response = $request->send();
        }catch (ClientErrorResponseException $e)
        {
            $this->handleException($e);
            return false;
        }
        if ($response->getStatusCode() == 200)
        {
            return true;
        }
        return false;
    }

    public function ban($url)
    {

    }

    private function handleException(\Exception $e)
    {
        if (App::environment() !== "production" && $this->forceException === 'auto')
            throw $e;

        if ($this->forceException === true)
            throw $e;
    }
}