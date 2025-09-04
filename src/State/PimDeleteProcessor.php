<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ProgramsInMajors;
use App\Repository\ProgramsRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @implements ProcessorInterface<mixed,mixed>
 */
final class PimDeleteProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<mixed,mixed> $decorated
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $decorated,
        private ProgramsRepository $progRepo,
    ) {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof ProgramsInMajors) {
            $count = $this->progRepo->count(['programInMajors' => $data]);
            if ($count > 0) {
                throw new ConflictHttpException(sprintf('Cannot delete because it is linked to %d program(s). Remove those links first.', $count), code: 409);
            }
        }

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
