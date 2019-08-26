<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class AbstractController {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param EntityManager $em
     * @return self
     */
    public function setEntityManager( EntityManager $em ) : self {
        $this->em = $em;
        return $this;
    }

    /**
     * @param Request $request
     * @return self
     */
    public function setRequest( Request $request ) : self {
        $this->request = $request;
        return $this;
    }
}