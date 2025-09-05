<?php
namespace App\Command;

use App\Entity\Stroke;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertStrokesCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setName('app:inspect-strokes')
            ->setDescription('Inspect Stroke rows and export candidates for manual vector conversion')
            ->addArgument('export', InputArgument::OPTIONAL, 'Path to write candidates JSON (default var/legacy_strokes.json)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(Stroke::class);
        $all = $repo->findAll();
        $legacy = [];
        foreach($all as $s){
            if($s->getVector() === null && $s->getData() !== null){
                $legacy[] = [
                    'id' => $s->getId(),
                    'dataPreview' => substr($s->getData(), 0, 200),
                    'createdAt' => $s->getCreatedAt()?->format(DATE_ATOM),
                ];
            }
        }
        $output->writeln(sprintf('Total strokes: %d, legacy candidates: %d', count($all), count($legacy)));
        $path = $input->getArgument('export') ?: __DIR__ . '/../../var/legacy_strokes.json';
        if(!empty($legacy)){
            @mkdir(dirname($path), 0777, true);
            file_put_contents($path, json_encode($legacy, JSON_PRETTY_PRINT));
            $output->writeln('Wrote candidates to: ' . $path);
        }

        return Command::SUCCESS;
    }
}
