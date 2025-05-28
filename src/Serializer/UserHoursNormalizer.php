<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class UserHoursNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private NormalizerInterface $normalizer,
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @param User $data
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $year = $this->requestStack
             ->getCurrentRequest()
             ?->query
             ->getInt('hoursYear', 0);

        if ($year > 0) {
            $sum = 0;
            foreach ($data->getSubjectLecturers() as $sl) {
                if ($sl->getSubject()->getProgram()->getPlanYear() === $year) {
                    $sum += $sl->getSubjectHours();
                }
            }
            $data->setHoursUsed($sum);
        }
        return $this->normalizer->normalize($data, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
