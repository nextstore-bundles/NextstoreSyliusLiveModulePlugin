<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Validator;

use Webmozart\Assert\Assert;

class ValidatorProduct
{
    public function validateCreateFromExcel(array $params)
    {
        //NAME
        Assert::keyExists($params, "name");
        Assert::notEmpty($params["name"]);
        Assert::notNull($params["name"]);

        //PRICE
        Assert::keyExists($params, "price");
        Assert::notEmpty($params["price"]);
        Assert::notNull($params["price"]);
    }
}
