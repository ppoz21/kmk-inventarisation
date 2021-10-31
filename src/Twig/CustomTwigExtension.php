<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CustomTwigExtension extends AbstractExtension
{

    private AuthorizationCheckerInterface $securityChecker;

    public function __construct(AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->securityChecker = $securityChecker;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_granted', [$this, 'isGranted']),
        ];
    }

    public function isGranted(string $role): bool
    {
        try {

            if ($this->securityChecker->isGranted('ROLE_ADMIN'))
                return true;
            return $this->securityChecker->isGranted($role);

        }
        catch (AuthenticationCredentialsNotFoundException $e)
        {
            return false;
        }
    }
}
