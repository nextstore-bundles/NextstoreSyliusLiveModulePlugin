<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Service;

use Nextstore\SyliusLiveModulePlugin\Model\Live;
use Nextstore\SyliusLiveModulePlugin\Service\ProductService;
use Nextstore\SyliusLiveModulePlugin\Uploader\AwsFileUploader;
use Nextstore\SyliusLiveModulePlugin\Validator\ValidatorFile;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LiveService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorFile $validatorFile,
        private AwsFileUploader $awsFileUploader,
        private ParameterBagInterface $parameterBag,
        private ProductService $productService
    ) {
    }
    public function importProductsFromExcel(
        UploadedFile $file,
        Live $live
    ): string {
        $this->validatorFile->validateExcel($file);

        try {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension !== "xls" && $extension !== "xlsx") {
                throw new \Exception("Unsupported file format");
            }

            $reader = IOFactory::createReader("Xlsx");
            if ($extension === "xls") {
                $reader = IOFactory::createReader("Xls");
            }

            $reader->setReadDataOnly(true);

            $fileFromAws = $this->awsFileUploader->upload($file);
            $spreadsheet = $reader->load($fileFromAws);
            $path =
                $this->parameterBag->get("kernel.project_dir") .
                "/public/" .
                $fileFromAws;
            unlink($path);

            $spreadsheet->getActiveSheet()->removeRow(1);
            $sheetData = $spreadsheet
                ->getActiveSheet()
                ->toArray(null, true, true, true);
            $i = 0;
            $totalRows = count($sheetData);
            foreach ($sheetData as $row) {
                if ($i % 100 == 0) {
                    gc_collect_cycles();
                }
                $productArgs = [
                    "name" => $row["A"],
                    "code" => $row["B"],
                    "price" => $row["C"],
                    "quantity" => $row["D"],
                    "is_featured" => (int) $row["E"] === 1,
                    "description" => $row["F"],
                ];
                $product = $this->productService->createProductFromExcel(
                    $productArgs
                );
                $live->addProduct($product);
                $this->em->persist($live);

                $i++;
            }
            $this->em->flush();
            return $i . "/" . $totalRows;
        } catch (\Exception $exception) {
            throw new FileException(
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }
}
