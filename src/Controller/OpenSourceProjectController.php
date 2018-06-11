<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Project\ProjectManager;

class OpenSourceProjectController extends Controller
{
    /**
     * @Route("/projects/open-source", name="create_open_source_project", methods={"POST"})
     */
    public function createOpenSourceProject(Request $request, ProjectManager $projectManager)
    {
        $projectManager->createProject($project);
    }
}