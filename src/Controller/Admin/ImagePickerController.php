<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/image-picker')]
final class ImagePickerController extends AbstractController
{
    private const int PER_PAGE = 12;

    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UploaderHelper $uploaderHelper,
    ) {}

    #[Route('', name: 'admin_image_picker_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $query = trim($request->query->get('q', ''));
        $page = max(1, $request->query->getInt('page', 1));

        $qb = $this->imageRepository->createQueryBuilder('i')->orderBy('i.createdAt', 'DESC');

        if ('' !== $query) {
            $qb->andWhere('i.originalName LIKE :query OR i.imageName LIKE :query')->setParameter(
                'query',
                '%' . $query . '%',
            );
        }

        $total = (int) (clone $qb)->resetDQLPart('orderBy')->select('COUNT(i.id)')->getQuery()->getSingleScalarResult();

        $images = $qb
            ->setFirstResult((max($page, 1) - 1) * self::PER_PAGE)
            ->setMaxResults(self::PER_PAGE)
            ->getQuery()
            ->getResult();

        return $this->render('admin/image_picker/_grid.html.twig', [
            'images' => $images,
            'query' => $query,
            'page' => $page,
            'pages' => (int) ceil($total / self::PER_PAGE),
        ]);
    }

    #[Route('/upload', name: 'admin_image_picker_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        if (!$this->isCsrfTokenValid('image_picker_upload', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $file = $request->files->get('imageFile');
        if (!$file instanceof UploadedFile) {
            return new JsonResponse(['error' => 'No file uploaded.'], Response::HTTP_BAD_REQUEST);
        }

        $image = new Image();
        $image->setImageFile($file);

        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $image->getId(),
            'url' => $this->uploaderHelper->asset($image, 'imageFile'),
            'label' => (string) $image,
        ]);
    }
}
