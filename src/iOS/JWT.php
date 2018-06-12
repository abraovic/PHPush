<?php
namespace abraovic\PHPush\iOS;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

class JWT
{
    private $key;
    private $secret;
    private $kid;
    private $issuer;

    /**
     *     @param string $kid
     *     @param string $issuer
     *     @param string $keyFile
     *     @param string | null $keySecret
     */
    function __construct(
        string $kid,
        string $issuer,
        string $keyFile,
        string $keySecret = null
    ) {
        $this->key = $keyFile;
        $this->issuer = $issuer;
        $this->kid = $kid;
        $this->secret = $keySecret;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        $private_key = JWKFactory::createFromKeyFile($this->key, $this->secret, [
            'kid' => $this->kid,
            'alg' => 'ES256',
            'use' => 'sig',
        ]);
        $payload = [
            'iss' => $this->issuer,
            'iat' => time(),
        ];
        $header = [
            'alg' => 'ES256',
            'kid' => $private_key->get('kid'),
        ];

        return JWSFactory::createJWSToCompactJSON(
            $payload,
            $private_key,
            $header
        );
    }
}