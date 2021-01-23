<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private HttpClientInterface $client;
    
    /**
     * HomeController constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function getFranceData(): array
    {
        return $this->getApi('FranceLiveGlobalData');
    }
    
    public function getAllData(): array
    {
        return $this->getApi('AllLiveData');
    }
    
    public function getDepartementData(string $department): array
    {
        return $this->getApi("LiveDataByDepartement?Departement=${department}");
    }
    public function getAllDataByDate(string $date): array
    {
        return $this->getApi("AllDataByDate?date=${date}");
    }
    
    private function getApi(string $var)
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            "https://coronavirusapi-france.now.sh/${var}"
        );
        return $response->toArray();
    }
}