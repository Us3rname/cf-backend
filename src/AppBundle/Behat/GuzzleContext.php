<?php
namespace AppBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Gorghoa\ScenarioStateBehatExtension\Context\ScenarioStateAwareContext;
use Gorghoa\ScenarioStateBehatExtension\ScenarioStateInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class GuzzleContext extends RawMinkContext implements ScenarioStateAwareContext
{

    /**
     * @var array
     *
     * @access private
     */
    private $storedResult;

    /**
     * @var ScenarioStateInterface
     */
    private $scenarioState;

    /**
     * Host Name
     */
    const HOST_NAME = 'http://crossfit.developer.lan:81/';

    /**
     * Guzzle client
     * @var client
     */
    protected $client;

    /**
     * Request data
     */
    protected $requestData;

    /**
     * Guzzle response
     * @var Response response
     */
    protected $response;

    /**
     * Guzzle response body
     * @var response
     */
    protected $responseBody;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::HOST_NAME
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setScenarioState(ScenarioStateInterface $scenarioState)
    {
        $this->scenarioState = $scenarioState;
    }

    /**
     * @Given I have some data to send
     */
    public function iHaveSomeDataToSend(TableNode $table)
    {
        $data = $table->getRowsHash();
        $this->requestData = $data;
    }

    /**
     * @When I send a POST request to :uri
     */
    public function iSendAPostRequestTo($uri)
    {
        $data =
            [
                'headers' => [
                    'Accept' => 'application/json; application/hal+json',
                ],
                'name_exercise' => "snatch",
            ];

        $this->response = $this->client->request('POST', 'app_dev.php' . $uri, $data);
        $this->responseBody = json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @When I send a authenticated POST request to :uri
     */
    public function iSendAAuthenticatedPostRequestTo($uri)
    {

        $data = [
            'headers' => [
                'Accept' => 'application/json; application/hal+json',
                'Authorization' => 'Bearer ' . $this->scenarioState->getStateFragment('token')->getToken(),
            ],
            'json' => $this->requestData

        ];
        $this->response = $this->client->post('app_dev.php' . $uri, $data);

        $this->responseBody = json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @When I send a authenticated GET request to :uri
     */
    public function iSendAAuthenticatedGetRequestTo($uri)
    {

        $data = [
            'headers' => [
                'Accept' => 'application/json; application/hal+json',
                'Authorization' => 'Bearer ' . $this->scenarioState->getStateFragment('token')->getToken(),
            ],
            'json' => $this->requestData

        ];
        $this->response = $this->client->get('app_dev.php' . $uri, $data);

        $this->responseBody = json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @Then /^the guzzle response status code should be (\d+)$/
     */
    public function theGuzzleResponseStatusCodeShouldBe($response_code)
    {
        assertEquals($response_code, $this->response->getStatusCode());
    }

    /**
     * @Then /^the guzzle response header should be json$/
     */
    public function theGuzzleResponseHeaderShouldBeJson()
    {
        assertEquals($this->response->getHeader('Content-Type')[0], 'application/json');
    }

    /**
     * Check response contains specified values from JSON
     *
     * Example: The the response contains the following values from JSON:
     *   """
     *     {
     *       "name": "Test Name",
     *       "users": [
     *         {
     *           "id": 3
     *         },
     *         {
     *           "id": 6
     *         }
     *       ]
     *     }
     *   """
     * Example: And the response contains the following value from JSON:
     *   """
     *     {
     *       "name": "Test Name"
     *     }
     *   """
     *
     * @param PyStringNode $string Values specified in feature as JSON
     *
     * @Then the response contains the following value(s) from JSON:
     */
    public function theResponseContainsTheFollowingValueFromJSON(
        PyStringNode $string
    ) {
        $this->compareValues(
            $this->responseBody,
            json_decode($this->addStoredValues($string->getRaw()), true)
        );
    }

    private function addStoredValues($string)
    {
        preg_match_all('/\{stored\[(.*?)\]\}/si', $string, $matches);
        $length = count($matches[0]);
        for ($i = 0; $i < $length; $i++) {
            $parts = explode('][', $matches[1][$i]);
            $value = $this->storedResult;
            foreach ($parts as $part) {
                if (isset($value[$part])) {
                    $value = $value[$part];
                }
            }
            $string = str_replace($matches[0][$i], $value, $string);
        }
        return $string;
    }

    private function compareValues($input, $control)
    {
        if (is_array($input) && is_array($control)) {
            $this->compareArrays($input, $control);
        } else {
            if ($input != $control) {
                throw new \Exception(
                    'Actual value ' . $input . ' does not match expected ' .
                    'value ' . $control
                );
            }
        }
    }

    private function compareArrays(array $input, array $control)
    {
        foreach ($control as $field => $expected) {
            if (!array_key_exists($field, $input)) {
                throw new \Exception(
                    sprintf(
                        'Expected value %s is missing from array ' .
                        'of actual values at position %s',
                        is_array($expected) ?
                            json_encode($expected) :
                            $expected,
                        $field
                    )
                );
            }
            $this->compareValues($input[$field], $expected);
        }
    }
}