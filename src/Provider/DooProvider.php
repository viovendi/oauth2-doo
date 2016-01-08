<?php
/**
 * This file is based on the Generic Provider from the league/oauth2-client library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with the league/oauth2-client library.
 */

namespace JPBernius\OAuth2\Client\Provider;


use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * OAuth 2.0 service provider for interaction with doo.net API using Bearer token authentication.
 */
class DooProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string
     */
    private $apiUrl = 'https://api.doo.net/v1/';

    /**
     * @var string
     */
    private $uriAuthorize = 'oauth';

    /**
     * @var string
     */
    private $uriAccessToken = 'oauth';

    /**
     * @var string
     */
    private $uriResourceOwnerDetails = 'organizers/current';

    /**
     * @var array|null
     */
    private $scopes = null;

    /**
     * @var string
     */
    private $scopeSeparator;

    /**
     * @var string
     */
    private $responseError = 'error';

    /**
     * @var string
     */
    private $responseCode;

    /**
     * @var string
     */
    private $responseResourceOwnerId = 'id';

    /**
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $possible   = $this->getConfigurableOptions();
        $configured = array_intersect_key($options, array_flip($possible));

        foreach ($configured as $key => $value) {
            $this->$key = $value;
        }

        // Remove all options that are only used locally
        $options = array_diff_key($options, $configured);

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns all options that can be configured.
     *
     * @return array
     */
    protected function getConfigurableOptions()
    {
        return [
            'urlAuthorize',
            'urlAccessToken',
            'uriResourceOwnerDetails',
            'scopeSeparator',
            'responseError',
            'responseCode',
            'scopes',
            'apiUrl'
        ];
    }

    public function getBaseUri()
    {
        return $this->apiUrl;
    }

    /**
     * @inheritdoc
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getBaseUri() . $this->uriAuthorize;
    }

    /**
     * @inheritdoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseUri() . $this->uriAccessToken;
    }

    /**
     * @inheritdoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getBaseUri() . $this->uriResourceOwnerDetails;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultScopes()
    {
        return $this->scopes;
    }

    /**
     * @inheritdoc
     */
    protected function getScopeSeparator()
    {
        return $this->scopeSeparator ?: parent::getScopeSeparator();
    }

    /**
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError])) {
            $error = $data[$this->responseError];
            $code  = $this->responseCode ? $data[$this->responseCode] : 0;
            throw new IdentityProviderException($error, $code, $data);
        }
    }

    /**
     * @inheritdoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new GenericResourceOwner($response, $this->responseResourceOwnerId);
    }

    /**
     * @param array $response
     * @param AbstractGrant $grant
     * @return AccessToken
     */
    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        if (isset($response['data'])) {
            $data = $response['data'];
            return new AccessToken($data);
        }

        throw new IdentityProviderException('Bad Request', 400, 'Data provided for issuing access_token is not correct');
        
    }
}
