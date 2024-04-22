<?php

namespace App\Command;
use Psr\Log\LoggerInterface;
use App\Service\ApiConnectorService;
use App\Service\ShopImporterService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:shop_importer',description: 'Import Shops Record From Static ENDPOINT',hidden: false,)]
class ShopImporterCommand extends Command {
    
    private $apiConnector;
    private $logger;
    private $shopImporter;

    CONST ENDPOINT = "https://test.pallinolabs.it/api/v1/shops.json";

    public function __construct(ApiConnectorService $apiConnector,LoggerInterface $logger,ShopImporterService $shopImporter)
    {   
        $this->logger = $logger;
        $this->apiConnector = $apiConnector;
        $this->shopImporter = $shopImporter;
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
            
            foreach ($data as $dataShop) {       
                $this->shopImporter->importData($dataShop);
            }

            $output->writeln(['Import Proccess ended Successfully']);
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln(['An error occurred while importing shop data: ' . $e->getMessage()]);
            $this->logger->error('An error occurred while importing shop data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}        
        

    
    
