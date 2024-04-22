<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use App\Service\ApiConnectorService;
use App\Service\DiscountImporterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:discount_importer',description: 'Import Discounts Record From Static ENDPOINT',hidden: false,)]
class DiscountImporterCommand extends Command {
    
    private $apiConnector;
    private $logger;
    private $discountImporter;

    CONST ENDPOINT = "https://test.pallinolabs.it/api/v1/offers.json";

    public function __construct(ApiConnectorService $apiConnector,LoggerInterface $logger,DiscountImporterService $discountImporter)
    {   
        $this->logger = $logger;
        $this->apiConnector = $apiConnector;
        $this->discountImporter = $discountImporter;
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln(['Starting Import Proccess']);

            $data = $this->apiConnector->getDataFromApi(self::ENDPOINT);
            if (!is_array($data)) {
                $this->logger->error('Failed to fetch data from EndPoint: ' . self::ENDPOINT);
                $output->writeln(['Failed to fetch data from EndPoint: ' . self::ENDPOINT]);
                return Command::FAILURE;
            }
            
            foreach ($data as $dataDiscount) {       
                $this->discountImporter->importData($dataDiscount);
            }

            $output->writeln(['Import Proccess ended Successfully']);
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln(['An error occurred while importing Discount data: ' . $e->getMessage()]);
            $this->logger->error('An error occurred while importing Discount data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}        
        

    
    
