<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Service;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Nextstore\SyliusLiveModulePlugin\Validator\ValidatorProduct;
use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusDropshippingCorePlugin\Model\ProductInterface as ModelProductInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelPricing;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Generator\SlugGenerator;
use Sylius\Component\Core\Model\TaxonInterface;
use Webmozart\Assert\Assert;

class ProductService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductFactoryInterface $productFactory,
        private ChannelContextInterface $channelContext,
        private ValidatorProduct $validatorProduct,
        private SlugGenerator $slugGenerator
    ) {
    }

    public function createProductFromExcel(array $params): ProductInterface
    {
        $channel = $this->channelContext->getChannel();
        try {
            $this->validatorProduct->validateCreateFromExcel($params);
            $productExists = $this->em->getRepository(ProductInterface::class)->findOneBy(["code" => $params["code"]]);
            if ($productExists instanceof ProductInterface) {
                /** @var ProductVariant $variant */
                $variant = $productExists->getVariants()->first();
                $channelPricing = $variant->getChannelPricingForChannel($channel);
                $channelPricing->setPrice((int) $params["price"] * 100);
                $this->em->persist($productExists);
                $this->em->flush();
                return $productExists;
            } else {
                return $this->createProduct($params);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function createProduct(array $params): ProductInterface
    {
        $channel = $this->channelContext->getChannel();
        $taxon = $this->em->getRepository(TaxonInterface::class)->findOneBy(["code" => "live"]);
        Assert::isInstanceOf($taxon, TaxonInterface::class);

        /** @var ProductInterface $product */
        $product = $this->productFactory->createWithVariant();
        $product->setOrderType('order');
        $product->setName($params["name"]);
        $product->setCode($params["code"] ?? $this->generateProductCode());
        $product->setSlug($this->slugGenerator->generate($params["name"]));
        $product->setDescription($params["description"]);
        $product->setEnabled(true);
        /** @var ModelProductInterface $product */
        // $product->set($params["is_featured"]);

        /** @var ProductVariant $variant */
        $variant = $product->getVariants()->first();
        $variant->setCode($product->getCode());
        $variant->setName($params["name"]);
        $variant->setOnHand((int) $params["quantity"]);
        $variant->setTracked((int) $params["quantity"] > 0);

        $this->createChannelPricing($variant, (int) $params["price"], (int) $params["price"]);

        $product->setMainTaxon($taxon);
        $product->addChannel($channel);
        $this->em->persist($variant);
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function createChannelPricing(
        ProductVariantInterface $variant,
        int $price,
        int $originalPrice
    ): ChannelPricing {
        $channel = $this->channelContext->getChannel();

        $cp = new ChannelPricing();
        $cp->setChannelCode($channel->getCode());
        $cp->setMinimumPrice(0);
        $cp->setOriginalPrice($originalPrice * 100);
        $cp->setPrice($price * 100);
        $cp->setProductVariant($variant);

        $this->em->persist($cp);

        return $cp;
    }

    public function generateProductCode(int $length = 10)
    {
        $bytes = random_bytes((int) ceil($length / 2));
        $code = substr(bin2hex($bytes), 0, $length);

        return $code;
    }
}
