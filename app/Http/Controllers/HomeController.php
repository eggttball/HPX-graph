<?php

//require_once 'vendor/autoload.php';

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GraphAware\Neo4j\Client\ClientBuilder;

class HomeController extends Controller
{
    public function index(Request $request) {
        $url = getenv('GRAPHENEDB_BOLT_URL');
        $username = getenv('GRAPHENEDB_BOLT_USER');
        $password = getenv('GRAPHENEDB_BOLT_PASSWORD');
        $config = \GraphAware\Bolt\Configuration::newInstance()
            ->withCredentials($username, $password)
            ->withTimeout(10)
            ->withTLSMode(\GraphAware\Bolt\Configuration::TLSMODE_REQUIRED);
        $driver = \GraphAware\Bolt\GraphDatabase::driver($url, $config);
        $client = $driver->session();

        $result = $client->run("MATCH path = (n)-[r]->(m) RETURN path ORDER BY n.created_date DESC LIMIT 40");
        return json_encode($result->getRecords());
    }
    
}