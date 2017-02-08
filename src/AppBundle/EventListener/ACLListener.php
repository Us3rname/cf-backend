<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 3-1-2017
 * Time: 22:35
 */

namespace AppBundle\EventListener;


use AppBundle\Event\UserRegistered;
use AppBundle\Repository\CrossfitUserRepository;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ACLListener
{

    private $aclProvider;
    private $tokenStorage;
    private $crossfitUserRepo;

    /**
     * ACLListener constructor.
     * @param $aclProvider
     * @param $tokenStorage
     * @param $crossfitUserRepo
     */
    public function __construct(MutableAclProviderInterface $aclProvider, TokenStorageInterface $tokenStorage,
                                CrossfitUserRepository $crossfitUserRepo)
    {
        $this->aclProvider = $aclProvider;
        $this->tokenStorage = $tokenStorage;
        $this->crossfitUserRepo = $crossfitUserRepo;
    }


    /**
     * Create ACL rules for when the user is Registered
     *
     * @param UserRegistered $userRegistered
     */
    public function onUserRegistered(UserRegistered $userRegistered)
    {
        // creating the ACL
        $objectIdentity = ObjectIdentity::fromDomainObject($this->crossfitUserRepo->find($userRegistered->getUserId()));
        $acl = $this->aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $user = $this->tokenStorage->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->aclProvider->updateAcl($acl);
    }
}