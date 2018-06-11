<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Project\RepositoryManager;

class OpenSourceController extends Controller
{
    /**
     * @Route("/projects/open-source", name="create_open_source_repository", methods={"POST"})
     */
    public function createRepository(Request $request, RepositoryManager $repositoryManager)
    {
        $projectManager->createProject($project);
    }
}