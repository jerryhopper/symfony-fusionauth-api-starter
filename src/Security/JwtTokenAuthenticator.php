<?php
namespace App\Security;

//use App\Entity\User;

use App\Security\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
//use Firebase\JWT\JWT;



//use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
// use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\JsonResponse;

use Psr\Log\LoggerInterface;


use JerryHopper\EasyJwt\Decode;
//use JerryHopper\ServiceDiscovery\Discovery;



class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        //$this->index();
    }
    public function index()
    {
        $this->logger->info('I just got the logger');
        $this->logger->error('An error occurred');

        $this->logger->critical('I left the oven on!', [
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ]);

        // ...
    }

    public function getCredentials(Request $request)
    {
        /*
         *  Get the token from the headers.
         **/
        $prefix = getenv(JWT_TOKEN_HEADERPREFIX);'Bearer';
        $name   = getenv(JWT_TOKEN_HEADER);'Authorization';

        if (!$request->headers->has($name)) {
            return ;
        }
        $authorizationHeader = $request->headers->get($name);
        if (empty($prefix)) {
            $token = $authorizationHeader;
        }else{
            $headerParts = explode(' ', $authorizationHeader);
            if (!(2 === count($headerParts) && 0 === strcasecmp($headerParts[0], $prefix))) {
                return;
            }
            $token = $headerParts[1];
        }

        if (!$token) {
            //error_log("no token!");
            return;
        }
        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /*
         * Decode the token.
         **/
        $discoveryUrl = getenv(FUSIONAUTH_DISCOVERY_URL);
        $audience     = getenv(FUSIONAUTH_CHECK_AUD);
        $issuer       = getenv(FUSIONAUTH_CHECK_ISS);

        try{
            $decoded = new Decode($credentials,$discoveryUrl,$audience,$issuer); //
        }catch(\Exception $e){
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        //$logger = $this->container->get('logger');
        //$this->logger->critical( $credentials);

        //if ($data === false) {
        //    throw new CustomUserMessageAuthenticationException('Invalid Token');
        //}

        /*
         *  Set the userObject.
         **/
        $userObject = new \App\Security\User();

        $userObject->setAud($decoded->aud);
        $userObject->setExp($decoded->exp);
        $userObject->setIat($decoded->iat);
        $userObject->setIss($decoded->iss);
        $userObject->setSub($decoded->sub);
        $userObject->setAuthenticationType($decoded->authenticationType);

        $userObject->setEmail($decoded->email);
        $userObject->setEmailVerified($decoded->email_verified);
        $userObject->setPreferredUsername($decoded->preferred_username);
        $userObject->setApplicationId($decoded->applicationId);
        $userObject->setRoles($decoded->roles);

        return $userObject;

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessage()
        ], 401);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // do nothing - let the controller be called
    }
    public function supportsRememberMe()
    {
        return false;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
        // called when authentication info is missing from a
        // request that requires it
        return new JsonResponse([
            'error' => 'auth required'
        ], 401);
    }

    public function supports(Request $request){
        return $request->headers->has('Authorization');
        $request->headers;
        if($request->headers->get('Authorization')){
            return true;
        }else{
            return false;
        }

    }

    //public function supports(Request $request){}

}