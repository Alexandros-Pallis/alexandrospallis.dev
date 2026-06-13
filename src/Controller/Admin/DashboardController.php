<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_project_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Alexandrospallis Dev');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addAssetMapperEntry('app');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(ProjectCrudController::class, 'Projects', 'fa fa-diagram-project');
        yield MenuItem::linkTo(SkillCategoryCrudController::class, 'Skill Categories', 'fa fa-layer-group');
        yield MenuItem::linkTo(SkillCrudController::class, 'Skills', 'fa fa-star');
        yield MenuItem::linkTo(TimelineEntryCrudController::class, 'Timeline', 'fa fa-timeline');
        yield MenuItem::linkTo(CredentialCrudController::class, 'Credentials', 'fa fa-certificate');
        yield MenuItem::linkTo(TagCrudController::class, 'Tags', 'fa fa-tags');
        yield MenuItem::linkTo(ImageCrudController::class, 'Images', 'fa fa-image');
    }
}
