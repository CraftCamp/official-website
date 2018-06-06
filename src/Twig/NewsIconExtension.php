<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use App\Entity\News;
use App\Entity\Community\News as CommunityNews;
use App\Entity\Project\News as ProjectNews;

class NewsIconExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('news_icon', [$this, 'newsIconFilter'], [
                'is_safe' => ['html']
            ]),
        ];
    }
    
    public function newsIconFilter(News $news, string $size): string
    {
        return "<i class='{$this->getIconClass($news->getCategory())} {$size}'></i>";
    }

    protected function getIconClass(string $category): string
    {
        switch($category) {
            case CommunityNews::CATEGORY_COMMUNITY_CREATION: return 'fas fa-plus-circle';
            case CommunityNews::CATEGORY_NEW_MEMBER: return 'fas fa-user-plus';
            case CommunityNews::CATEGORY_NEW_PROJECT: return 'fas fa-paper-plane';
            case ProjectNews::CATEGORY_NEW_COMMUNITY: return 'fas fa-users';
            case ProjectNews::CATEGORY_NEW_MEMBER: return 'fas fa-user-plus';
            case ProjectNews::CATEGORY_NEW_RELEASE: return 'fas fa-rocket';
            case ProjectNews::CATEGORY_NEW_REPOSITORY: return 'fas fa-cogs';
        }
    }
}