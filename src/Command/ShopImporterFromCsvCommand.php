<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use App\Service\CsvDataReaderService;
use App\Service\ShopImporterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:csv_shop_importer',description: 'Import Shops Record from CSV File given the Path as an input Parameter',hidden: false,)]
class ShopImporterFromCsvCommand extends Command {
    

    private $csvReader;
    private $logger;
    private $shopImporter;

    public function __construct(LoggerInterface $logger,ShopImporterService $shopImporter, CsvDataReaderService $csvReader)
    {   
        $this->logger = $logger;
        $this->csvReader = $csvReader;
        $this->shopImporter = $shopImporter;
        parent::__construct();
    }

    protected function configure(){
        $this->addArgument('FilePath', InputArgument::REQUIRED);

    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln(['Starting Import Proccess']);

            $csvFullPath = $input -> getArgument('FilePath');
            $data = $this->csvReader->processFile($csvFullPath);
            if (empty($data)) {
                $this->logger->error('Failed to process CSV data from File: ' . $csvFullPath);
                $output->writeln(['Failed to process CSV data from File: ' . $csvFullPath]);
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
        

    
    
