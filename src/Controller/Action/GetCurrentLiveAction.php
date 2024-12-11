<?php

namespace Nextstore\SyliusLiveModulePlugin\Controller\Action;

use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusLiveModulePlugin\Model\Live;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetCurrentLiveAction extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer)
    {
    }

    public function __invoke(Request $request)
    {
        $currentDate = new \DateTime();

        $currentDate = new \DateTime();

        $timeWindow = new \DateInterval('P1D'); // 1 day
        $upcomingDate = (clone $currentDate)->add($timeWindow);

        $live = $this->em->getRepository(Live::class)->createQueryBuilder('l')
            ->andWhere('l.enabled = :enabled')
            ->andWhere('l.startDate > :currentDate')
            ->andWhere('l.startDate <= :upcomingDate')
            ->setParameter('currentDate', $currentDate)
            ->setParameter('upcomingDate', $upcomingDate)
            ->setParameter('enabled',true)
            ->orderBy('l.startDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (!$live instanceof Live) {
            return new JsonResponse(null);
        }

        $res = $this->normalizer->normalize($live, null, ['groups' => 'shop:live:read']);
        return new JsonResponse(["data" =>$res]);
    }
}
