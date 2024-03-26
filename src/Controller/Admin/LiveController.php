<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Controller\Admin;

use Nextstore\SyliusLiveModulePlugin\Model\Live;
use Nextstore\SyliusLiveModulePlugin\Service\LiveService;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmozart\Assert\Assert;

class LiveController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $em,
        private LiveService $liveService,
        private CurrencyContextInterface $currencyContext
    ) {
    }

    public function detail(Request $request)
    {
        $id = $request->get("id");
        $live = $this->em->getRepository(Live::class)->find($id);
        Assert::isInstanceOf($live, Live::class);
        $currencyCode = $this->currencyContext->getCurrencyCode();

        return new Response(
            $this->twig->render(
                "@NextstoreSyliusLiveModulePlugin/Admin/Live/detail.html.twig",
                [
                    "live" => $live,
                    "currencyCode" => $currencyCode,
                ]
            )
        );
    }

    public function importProducts(Request $request)
    {
        $referer = (string) $request->headers->get("referer");
        try {
            $id = $request->get("id");
            $live = $this->em->getRepository(Live::class)->find($id);
            Assert::isInstanceOf($live, Live::class);

            $file = $request->files->get("excel-file");
            $importedRows = $this->liveService->importProductsFromExcel(
                $file,
                $live
            );
            $this->addFlash("success", "Succesfully imported " . $importedRows);
        } catch (\Exception $e) {
            $this->addFlash("error", $e->getMessage());
        }
        return new RedirectResponse($referer);
    }

    public function removeProduct(Request $request)
    {
        $referer = (string) $request->headers->get("referer");
        try {
            $id = $request->get("id");
            $live = $this->em->getRepository(Live::class)->find($id);
            Assert::isInstanceOf($live, Live::class);

            $productId = $request->get("productId");
            /** @var Product $product */
            $product = $this->em
                ->getRepository(Product::class)
                ->find((int) $productId);
            Assert::isInstanceOf($product, Product::class);

            $live->removeProduct($product);
            $this->em->persist($live);
            $this->em->flush();

            $this->addFlash(
                "success",
                "Succesfully removed " . $product->getName()
            );
        } catch (\Exception $e) {
            $this->addFlash("error", $e->getMessage());
        }
        return new RedirectResponse($referer);
    }

    public function featureProduct(Request $request)
    {
        $referer = (string) $request->headers->get("referer");
        try {
            $id = $request->get("id");
            /** @var Live $live */
            $live = $this->em->getRepository(Live::class)->find($id);
            Assert::isInstanceOf($live, Live::class);

            $productId = $request->get("productId");
            /** @var Product $product */
            $product = $this->em
                ->getRepository(Product::class)
                ->find((int) $productId);
            Assert::isInstanceOf($product, Product::class);

            $live->setFeaturedProduct($product);
            $this->em->persist($live);
            $this->em->flush();

            $this->addFlash(
                "success",
                "Succesfully featured " . $product->getName()
            );
        } catch (\Exception $e) {
            $this->addFlash("error", $e->getMessage());
        }
        return new RedirectResponse($referer);
    }
}
